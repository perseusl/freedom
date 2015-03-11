<?php
return array(
    'auth_server' => [
            'url' => 'apidev.freedom.tm',
            'port' => 3000,
        ],
    'backend_server' => [
            'url' => 'apidev.freedom.tm',
            'port' => 8000,
        ],
    'basic_scopes' => "web.view,mobile.view,self.view,self.edit,self.delete",
    'basic_roles' => ['all', 'recruiter', 'payout'],
    
    'scopes' =>
        [

            'all' => [
                    'self.view',
                    'self.edit',
                    'self.delete',
                    'self.logout',
                    'self.view_activities',
                    'self.view_points',
                    'self.add_points',
                    ],
            'recruiter' => [
                    'recruiter.view',
                    'recruiter.earnings',
                    'prospect.find',
                    'prospect.add',
                    'prospect.view',
                    'prospect.update',
                    'prospect.delete',
                    'recruiter.join_network',
                    ],
            'partner' => [
                    'channel.add',
                    'channel.view',
                    'channel.switch_network',
                    'channel.delete',
                    'channels.leaderboard',
                    'partner.upgrade_to_network',
                    'partner.earnings',
                    'partner.view_network_feed',
                    ],
            'network' => [
                    'network.accept',
                    'network.edit',
                    'network.view',
                    'network.unpartner',
                    'network.approve_share',
                    'network.get_share',
                    'user.view',
                    'network.feed.add',
                    'network.feed.delete',
                    'network.recruits',
                    'network.spotlight',
                    ],
            'admin' => [
                    'admin.edit_all',
                    'admin.create_all',
                    'admin.delete_all',
                    'admin.view_all',
                    'admin.view_as',
                    ],
            'super_admin' => [
                    'super_admin.all',
                    ],
            'payout' => [
                    'payout.view',
                    ]


        ]
);
