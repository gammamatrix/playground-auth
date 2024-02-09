<?php
/**
 * Playground
 */
namespace Playground\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * \Playground\Models\Interfaces\WithModifierInterface
 */
interface WithModifierInterface
{
    /**
     * Get the modifier of the model.
     */
    public function modifier(): HasOne;
}
