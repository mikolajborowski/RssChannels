<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RssChannels;
use Exception;
use App\Mail\RssContentMailing;
use Illuminate\Support\Facades\Mail;
class RssChannelsController extends Controller
{
    public function formSubmit(Request $request)
    {
        $response = [];
        $all = RssChannels::all();
        foreach ($all as $k => $channel) {
            try {
                $response[] = ['data' => $this->prettifyData($channel)];
            } catch (Exception $e) {
                $response[] = ['data' => '<br/> <h3 style="color:red;">Unable to load channel (' . $channel["channel"] . ') </h3>'];
            }
        }
        try {
            $this->sendEmail($request->get('email'), $response);
        } catch (Exception $e) {
        }
        return response()->json($response);
    }

    private function prettifyData($channel)
    {
        $response = '';
        $data = simplexml_load_file($channel['channel'], 'SimpleXMLElement', LIBXML_NOCDATA);
        $array = json_decode(json_encode((array) $data), TRUE);
        $array = $array['channel'];
        $title = $array['title'];
        $response .= ' <h3> --- Channel: ' . $title . ' --- </h3>';
        $description = $array['description'];
        $response .= ' <h5> Description: ' . $description . '</h5>';
        $link = $array['link'];
        $response .= ' <h5> Link: ' . $link . '</h5>';
        $language = $array['language'];
        $response .= ' <h5> Language: ' . $language . '</h5>';
        $copyright = $array['copyright'];
        $response .= ' <h5> COPYRIGHTS: ' . $copyright . '</h5>';
        $response .= $this->formatArticle($array['item']);
        return $response;
    }

    private function formatArticle($article_array)
    {
        $response = '';
        try {
            foreach ($article_array as $k => $article) {
                $response .= '<h4 style="color: #F5F5F5">---------------------</h4>';
                $response .= '<h4 style="color: #B22222">' . $article["title"] . '</h4>';
                $response .= '<h5 style="color: blue">Link: ' . $article["link"] . '</h5>';
                $response .= '<p>' . $article["description"] . '</p>';
                $response .= '<h5 style="color: blue">Date: ' . $article["pubDate"] . '</h5>';
                $response .= '<h4 style="color: #F5F5F5">---------------------</h4>';
            }
            return $response;
        } catch (Exception $e) {
            return '';
        }
    }

    private function sendEmail($email, $response)
    {
        $data = [
            'adress' => $email,
            'message' => $response
        ];

        Mail::to($email)->send(new RssContentMailing($data));
    }
    public function channelSubmit(Request $request)
    {
        $request->validate([
            'channel' => 'required'
        ]);
        $share = new RssChannels([
            'channel' => $request->get('channel')
        ]);
        $share->save();
        return 200;
    }

    public function deleteChannel(Request $request)
    {
        $delete = RssChannels::find($request->get('id'));
        $delete->delete();
        return 200;
    }

    public function getChannelsList(Request $request)
    {
        $all = RssChannels::all();
        return response()->json($all);
    }
}
