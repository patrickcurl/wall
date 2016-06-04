<?php

namespace App;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use Cache;
use Carbon\Carbon;
use Wallhaven\Category;
use Wallhaven\Order;
use Wallhaven\Purity;
use Wallhaven\Sorting;
use Wallhaven\Wallhaven;
use \Conner\Tagging\Taggable;
class Wallpaper extends Model
{
  use Sluggable;
  public function $sluggable(){
    return [
      "slug" => [

      ]
    ]
  }
  protected $fillable = ['reddit_id', 'title', 'source', 'domain', 'width', 'height', 'score', 'thumb', 'url', 'permalink'];

  public static function getWH(){
    $wh = new WallHaven();
    $wallpapers = $wh->filter()->keywords('landscape')->getWallpapers();
    foreach($wallpapers as $w){
      echo $w->getResolution() . PHP_EOL;
    }
  }

  public static function getReddit($sub = '/r/wallpapers', $after=0){
    $client = new Client([
        'base_uri' => 'https://www.reddit.com/r/wallpapers/search.json',
        'timeout' => 25.0,
        'headers' => ['User-Agent' => 'wallpaper-reddit php script by /u/zvive']
      ]);

      //if($after != null){
        $resp = $client->request('GET', '', [
          'query' => [
            'after' => $after,
            'q' => "site%3Aimgur.com",
            'restrict_sr' => 'on',
            'sort' => "relevance",
            't' => "all.json"
          ]
        ]);
      // } else {
      //   $resp = $client->request('GET', '', [
      //     'query' => [
      //       'q' => "site%3Aimgur.com",
      //       'restrict_sr' => 'on',
      //       'sort' => "relevance",
      //       't' => "all.json"
      //     ]
      //   ]);
      // }
      return collect(json_decode($resp->getBody()));
  }
  public static function insertWalls($count=0, $after=null){

      //$redditArray = [];
      $redditData = self::getReddit('/r/wallpapers', $after)['data'];

      foreach($redditData->children as $child)

      {
        echo "$count \n";
        $count++;
        if(
          //  property_exists($child->data, 'post_hint') && $child->data->post_hint == "image" &&
            property_exists($child->data, 'title') && property_exists($child->data, 'id') &&
            // property_exists($child->data, 'domain') &&
            property_exists($child->data, 'preview') &&
            property_exists($child->data, 'score') &&
            property_exists($child->data, 'thumbnail') &&
            property_exists($child->data, 'url') &&
            property_exists($child->data, 'permalink')
            )
          {
              //$count++;
              $redditArray = [];
              $properties = ['title', 'id', 'domain', 'score', 'thumbnail', 'url', 'permalink'];
               foreach($properties as $prop){
                 if(property_exists($prop)){
                    $redditArray[$prop] = $child->data->$prop;
                 }
               }

               foreach($preview as $p){

               }
              $redditArray =
              [
                'title'     => $child->data->title,
                'reddit_id' => $child->data->id,
                'domain'    => $child->data->domain,
                'source'    => $child->data->preview->images[0]->source->url,
                'width'     => $child->data->preview->images[0]->source->width,
                'height'    => $child->data->preview->images[0]->source->height,
                'score'     => $child->data->score,
                'url'       => $child->data->url,
                'thumb'     => $child->data->thumbnail,
                'permalink' => $child->data->permalink,
              ];
              $wp = self::where('reddit_id', '=', $redditArray['reddit_id'])->first();
              if(!$wp){
                $wp = self::firstOrCreate($redditArray);
              } else {
                $wp->title = $redditArray['title'];
                $wp->reddit_id = $redditArray['reddit_id'];
                $wp->domain = $redditArray['domain'];
                $wp->source = $redditArray['source'];
                $wp->width = $redditArray['width'];
                $wp->height = $redditArray['height'];
                $wp->score = $redditArray['score'];
                $wp->url = $redditArray['url'];
                $wp->thumb = $redditArray['thumb'];
                $wp->permalink = $redditArray['permalink'];
                $wp->save();
              }

          }

      }


      #$expires = Carbon::now()->addMinutes(60);
      #Cache::put('after', $c['data']->after, $expires);

      //if($count <= 200){
        #exitecho $redditData->after;
        //echo count($redditArray) . "\n";
        self::insertWalls($count, $redditData->after);
      // }
        //return $redditArray;

      //enchant_broker_dict_existsecho $count;




      //return var_dump($c['data']->children[1]->data->url);
  }


}
