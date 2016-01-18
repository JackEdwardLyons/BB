// <config>
        date_default_timezone_set('America/Los_Angeles');
        mb_internal_encoding("UTF-8");

        $typeformApiKey='4457e1d840dddb3ec9fe8b6476b074abbc9aee11';
        $typeformFormId='PMv4Up';
        $typeformEmailField='textfield_7302951';
        $typeformNameField='textfield_7302949';
        $previouslyInvitedEmailsFile=__DIR__.'/previouslyInvitedEmails.json'; 



*** // file_put_contents($previouslyInvitedEmailsFile,json_encode($previouslyInvitedEmails)); // I am not sure where this goes ***




        // your slack team/host name 
        $slackHostName='betabuddy';

        // find this when checking the post at https://betabuddy.slack.com/admin/invites/full
        $slackAutoJoinChannels='C02RWGV3X,C02S05WJA,C02SU0WLE,C02S2B5CH,C02RVB0CK,C02SPEMBY';
        // generate token at https://api.slack.com/
        $slackAuthToken='xoxp-5057327701-5057327703-5127195752-517cac';

// <get typeform emails>
        if(@!file_get_contents($previouslyInvitedEmailsFile)) {
                $previouslyInvitedEmails=array();
        }
        else {
                $previouslyInvitedEmails=json_decode(file_get_contents($previouslyInvitedEmailsFile),true);
        }
        $offset=count($previouslyInvitedEmails);

        $typeformApiUrl='https://api.typeform.com/v0/form/'.$typeformFormId.'?key='.$typeformApiKey.'&completed=true&offset='.$offset;

        if(!$typeformApiResponse=file_get_contents($typeformApiUrl)) {
                echo "Sorry, can't access API";
                exit;
        }

        $typeformData=json_decode($typeformApiResponse,true);

        $usersToInvite=array();
        foreach($typeformData['responses'] as $response) {
                $user['email']=$response['answers'][$typeformEmailField];
                $user['name']=$response['answers'][$typeformNameField];
                if(!in_array($user['email'],$previouslyInvitedEmails)) {
                        array_push($usersToInvite,$user);
                }
        }
// </get typeform emails>


/ <invite to slack>
        $slackInviteUrl='https://'.$betabuddy.'.slack.com/api/users.admin.invite?t='.time();

        $i=1;
        foreach($usersToInvite as $user) {
                echo date('c').' - '.$i.' - '."\"".$user['name']."\" <".$user['email']."> - Inviting to ".$betabuddy." Slack\n";

                // <invite>
                        $fields = array(
                                'email' => urlencode($user['email']),
                                'channels' => urlencode($slackAutoJoinChannels),
                                'first_name' => urlencode($user['name']),
                                'token' => $slackAuthToken,
                                'set_active' => urlencode('true'),
                                '_attempts' => '1'
                        );

                        // url-ify the data for the POST
                                $fields_string='';
                                foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
                                rtrim($fields_string, '&');

                        // open connection
                                $ch = curl_init();

                        // set the url, number of POST vars, POST data
                                curl_setopt($ch,CURLOPT_URL, $slackInviteUrl);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch,CURLOPT_POST, count($fields));
                                curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

                        // exec
                                $replyRaw = curl_exec($ch);
                                $reply=json_decode($replyRaw,true);
                                if($reply['ok']==false) {
                                        echo date('c').' - '.$i.' - '."\"".$user['name']."\" <".$user['email']."> - ".'Error: '.$reply['error']."\n";
                                }
                                else {
                                        echo date('c').' - '.$i.' - '."\"".$user['name']."\" <".$user['email']."> - ".'Invited successfully'."\n";
                                }

                        // close connection
                                curl_close($ch);

                                array_push($previouslyInvitedEmails,$user['email']);

                // </invite>
                $i++;
        }
// </invite to slack>

// </config>

