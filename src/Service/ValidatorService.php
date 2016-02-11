<?php

namespace Service;

use Respect\Validation\Validator as v;

/**
 * Class ValidatorService
 *
 * Provides methods for validating objects and arrays.
 *
 * @package Service
 */
class ValidatorService extends Service
{

    /**
     * Validates the request body against a definition for an activity.
     * If the validation fails, 400 status is outputted.
     */
    public static function validateApiActivity() {
        $app = \Slim\Slim::getInstance();

        $activity = json_decode($app->request->getBody());
        $validator =
            v::attribute('title', v::strType()->notEmpty())
                ->attribute('difficulty', v::in(\Model\Enum\Level::toArray()))
                ->attribute('guidance', v::in(\Model\Enum\Level::toArray()))
                ->attribute('motivation', v::in(\Model\Enum\Level::toArray()))
                ->attribute('elaboration', v::strType())
                ->attribute('activityAreas', v::arrType()->each(
                    v::in(\Model\Enum\ActivityArea::toArray())
                ))
                ->attribute('groups', v::arrType()->each(
                    v::in(\Model\Enum\GroupType::toArray())
                ))
                ->attribute('planning', v::arrType()->each(
                    v::attribute('duration', v::intVal()->min(0))
                        ->attribute('description', v::strType())
                ))
                ->attribute('checklist', v::arrType()->each(
                    v::strType()
                ))
                ->attribute('materials', v::arrType()->each(
                    v::attribute('amount', v::intVal()->min(0))
                        ->attribute('description', v::strType())
                ))
                ->attribute('budget', v::arrType()->each(
                    v::attribute('amount', v::intVal()->min(0))
                        ->attribute('description', v::strType())
                        ->attribute('cost', v::floatType())
                ));

        try {
            $validator->assert($activity);
        } catch (\Exception $exception) {
            $app->halt(400, $exception->getFullMessage());
        }
    }

}