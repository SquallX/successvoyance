<?php
namespace App\Models;

//  Models
use App\Models\Forum\Post;
use App\Models\Forum\Topic;
use App\Models\Forum\TopicTrack;
use App\Models\Forum\ForumTrack;

//  Illuminate
use App\Presenters\DatePresenter;
use App\Presenters\UserPresenter;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

//  Utils
use Date;

/**
 * Class User
 * @author Alexandre Ribes
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable, SoftDeletes, DatePresenter, UserPresenter;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'firstname', 'nickname', 'email', 'password', 'avatar', 'dob', 'website', 'job', 'country', 'city', 'biography', 'can_contact', 'can_full_name', 'can_newsletter', 'can_astrological', 'can_age', 'last_connexion', 'deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $dates = ['last_connexion', 'deleted_at'];

    protected $with = ['roles'];

    /**
     * Sujets crées par l'utilisateur
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics()
    {
        return $this->hasMany(Topic::class, 'user_id');
    }

    /**
     * Messages crées par l'utilisateur
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    /**
     * Suivi des sujets lus par l'utilisateur
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topicsTracks()
    {
        return $this->hasMany(TopicTrack::class, 'user_id');
    }

    /**
     * Suivi des forums lus par l'utilisateur
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function forumsTracks()
    {
        return $this->hasMany(ForumTrack::class, 'user_id');
    }

    /**
     * Roles de l'utilisateur
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_users', 'user_id');
    }

    /**
     * Enregistrement dans la newsletter pour l'utilisateur
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function newsletter()
    {
        return $this->hasOne(Newsletter::class, 'user_id');
    }

    /**
     * Commentaires de l'utilisateur
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    /**
     * Votes de l'utilisateur
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ratings()
    {
        return $this->belongsTo(Rating::class, 'user_id');
    }

    /**
     * Voyant associé à l'utilisateur
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function soothsayer()
    {
        return $this->hasOne(Soothsayer::class, 'user_id');
    }

    /**
     * Voyants favoris
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoritesSoothsayers()
    {
        return $this->belongsToMany(Soothsayer::class, 'soothsayers_favorites', 'user_id');
    }

    /**
     * Retourne tous les achats emails
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function emails()
    {
        return $this->hasMany(TellingEmailUser::class, 'user_id');
    }

    /**
     * Retourne le dernier achat email
     *
     * @return mixed
     */
    public function lastEmail()
    {
        return $this->emails()->latest()->first();
    }

    /**
     * Formatage de la date de naissance
     *
     * @param $value
     */
    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = is_null($value) || empty($value) ? null : Date::parse($value)->format('Y-m-d');
    }
}
