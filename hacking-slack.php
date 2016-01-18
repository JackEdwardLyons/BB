// <config>
        date_default_timezone_set('America/Los_Angeles');
        mb_internal_encoding("UTF-8");

        $typeformApiKey='4457e1d840dddb3ec9fe8b6476b074abbc9aee11';
        $typeformFormId='PMv4Up';
        $typeformEmailField='textfield_7302951';
        $typeformNameField='textfield_7302949';
        $previouslyInvitedEmailsFile=__DIR__.'/previouslyInvitedEmails.json';

        // your slack team/host name 
        $slackHostName='hashtagnomads';

        // find this when checking the post at https://nomadslack.slack.com/admin/invites/full
        $slackAutoJoinChannels='C02RWGV3X,C02S05WJA,C02SU0WLE,C02S2B5CH,C02RVB0CK,C02SPEMBY';
        // generate token at https://api.slack.com/
        $slackAuthToken='xoxp-2551684328';
// </config>