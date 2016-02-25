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
            v::attribute('title', v::stringType()->notEmpty())
                ->attribute('difficulty', v::in(\Model\Enum\Level::toArray()))
                ->attribute('guidance', v::in(\Model\Enum\Level::toArray()))
                ->attribute('motivation', v::in(\Model\Enum\Level::toArray()))
                ->attribute('groupSizeMin', v::optional(v::intType()->min(1)))
                ->attribute('groupSizeMax', v::optional(v::intType()->min(1)
                    ->min((isset($activity->groupSizeMin) ? $activity->groupSizeMin : 1))))
                ->attribute('elaboration', v::stringType())
                ->attribute('activityAreas', v::arrayType()->each(
                    v::in(\Model\Enum\ActivityArea::toArray())
                ))
                ->attribute('groups', v::arrayType()->each(
                    v::in(\Model\Enum\GroupType::toArray())
                ))
                ->attribute('planning', v::arrayType()->each(
                    v::attribute('duration', v::intType()->min(0))
                        ->attribute('description', v::stringType())
                ))
                ->attribute('checklist', v::arrayType()->each(
                    v::stringType()
                ))
                ->attribute('materials', v::arrayType()->each(
                    v::attribute('amount', v::intType()->min(0))
                        ->attribute('description', v::stringType())
                ))
                ->attribute('budget', v::arrayType()->each(
                    v::attribute('amount', v::intType()->min(0))
                        ->attribute('description', v::stringType())
                        ->attribute('cost', v::floatVal())
                ));

        try {
            $validator->assert($activity);
        } catch (\Exception $exception) {
            $app->halt(400, $exception->getFullMessage());
        }
    }

}