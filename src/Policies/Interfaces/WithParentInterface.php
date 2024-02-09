<?php
/**
 * Playground
 */
namespace Playground\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * \Playground\Models\Interfaces\WithParentInterface
 */
interface WithParentInterface
{
    /**
     * Get the parent of the model.
     */
    public function parent(): HasOne;
}
