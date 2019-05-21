<?php

namespace App\Api;

class GithubApi extends Api
{
    /**
     * Calculates score of inputted keyword.
     * First checks cache for keyword if not fetches new data and calculates 
     *
     * @param string $keyword
     * @return array
     * 
     * @since 1.0.0
     */
    public function getScore($keyword)
    {
        $count_pos = 0;
        $count_neg = 0;
        $total = 0;

        $cached = $this->getCache(
            [
                'date_expires',
                'score'
            ],
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
                return json_encode([
                    'term' => $keyword,
                    'msg' => 'Keyword not found!'
                ]);
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

        return json_encode([
            'term' => $keyword,
            'score' => $score,
        ]);
    }
}
