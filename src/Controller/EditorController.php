<?php
// /src/Controller/EditorController.php

namespace Controller;
use \Model\Enum\ActivityArea;

/**
 * Class EditorController
 *
 * @package Controller
 */
class EditorController extends Controller
{

    public function newAction() {
        $params = [
//            'activityAreas' => [
//                'ChallengingScoutingTechniques' => ActivityArea::ChallengingScoutingTechniques,
//                'Expression' => ActivityArea::Expression,
//                'SportsAndGames' => ActivityArea::SportsAndGames,
//                'OutdoorLife' => ActivityArea::OutdoorLife,
//                'Identity' => ActivityArea::Identity,
//                'International' => ActivityArea::International,
//                'Society' => ActivityArea::Society,
//                'SafeAndHealthy' => ActivityArea::SafeAndHealthy
//            ]
        ];
        $this->app->render('pages/editor.html', $params);
    }

}