<?php
namespace App\Models;

//  Models
use App\Models\Content\Picture;
use Illuminate\Database\Eloquent\Model;

//  Traits
use App\Presenters\DatePresenter;
use App\Presenters\AstrologicalSignPresenter;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class AstrologicalSign
 * @author Alexandre Ribes
 * @package App
 */
class AstrologicalSign extends Model
{
    use SoftDeletes, AstrologicalSignPresenter, DatePresenter;

    protected $table = 'astrological_signs';

    protected $fillable = ['name', 'slug', 'logo', 'content', 'begin_at', 'ending_at', 'deleted_at'];

    protected $dates = ['begin_at', 'ending_at', 'deleted_at'];

    /**
     * Horoscopes des signes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function horoscopes()
    {
        return $this->hasMany(Horoscope::class, 'sign_id');
    }

    public function lastHoroscope()
    {
        return $this->hasOne(Horoscope::class)->orderBy('created_at', 'DESC')->first();
    }

    /**
     * Images associées
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pictures()
    {
        return $this->belongsToMany(Picture::class, 'astrological_signs_pictures', 'sign_id');
    }
}
