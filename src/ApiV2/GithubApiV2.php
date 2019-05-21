<?php

namespace App\ApiV2;

class GithubApiV2 extends ApiV2
{

    /**
     * Calculates score of inputted keyword.
     * First checks cache for keyword, if not, fetches new data and calculates
     *
     * Returns data in jsonapi specs
     *
     * @param string $keyword
     * @return array
     *
     * @since 1.0.0
     */
    protected function getScore($keyword)
    {
        if ($this->request_check !== 200) {
            return [
                'content' => '',
                'status' => $this->checkRequest(),
                'headers' => ['Status' => $this->checkRequest()]
            ];
        }

        $cached = $this->getCache(
            ['score'],
            'search_issues',
            "term = ? AND date_expires >= NOW()",
            [$keyword]
        );

        if (!empty($cached) && isset($cached[0]['score']) && $cached[0]['score'] !== '') {
            $score = $cached[0]['score'];
        } else {
            $positive = $this->get(
                '?q='.$keyword.'+rocks',
                '/search/issues',
                'github'
            );
    
            $negative = $this->get(
                '?q='.$keyword.'+sucks',
                '/search/issues',
                'github'
            );
    
            
            if ((empty($positive) && empty($negative)) || ($positive->total_count === 0 && $negative->total_count === 0)) {
                return [
                    'content' => json_encode([
                        'errors' => [
                            'msg' => 'Keyword not found'
                        ],
                        'jsonapi' => [
                            'version' => '1.0',
                        ],
                    ]),
                    'status' => 200,
                    'headers' => ['Content-Type' => 'application/vnd.api+json']
                ];
            }

            if (!empty($positive)) {
                $count_pos = $positive->total_count;
            }
            if (!empty($negative)) {
                $count_neg = $negative->total_count;
            }
    
            $total = $count_pos + $count_neg;
    
            $score = number_format(($count_pos/$total)*10, 2, '.', '');

            $this->deleteCache("term = ?", [$keyword], 'search_issues');

            $this->setCache(
                [
                    'term' => '?',
                    'score' => '?',
                    'date_expires' => 'DATE_ADD(NOW(), INTERVAL '. self::CACHE_EXPIRY .')',
                    'date_cached' => 'NOW()'
                ],
                [
                    $keyword,
                    $score
                ],
                'search_issues'
            );
        }

        return [
            'content' => json_encode([
                'data' => [
                    'type' => 'score',
                    'id' => $keyword,
                    'attributes' => [
                        'score' => $score,
                    ]
                ],
                'jsonapi' => [
                    'version' => '1.0',
                ],
            ]),
            'status' => 200,
            'headers' => ['Content-Type' => 'application/vnd.api+json']
        ];
    }
}
