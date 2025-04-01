<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = ['name'];

    /**
     * Get the books associated with an author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    /**
     * Retrieve an author by name or create a new one if it doesn't exist.
     *
     * @param string $name
     * @return \App\Author
     */
    public static function findOrCreateByName($name)
    {
        return self::firstOrCreate(['name' => $name]);
    }
}
