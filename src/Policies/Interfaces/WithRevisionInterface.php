<?php
/**
 * Playground
 */
namespace Playground\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * \Playground\Models\Interfaces\WithRevisionInterface
 */
interface WithRevisionInterface
{
    /**
     * Get the revisions of the model.
     */
    public function revisions(): HasMany;
}
