<?php
/**
 * Playground
 */
namespace Playground\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * \Playground\Models\Interfaces\WithCreatorInterface
 */
interface WithCreatorInterface
{
    /**
     * Get the creator of the model.
     */
    public function creator(): HasOne;
}
