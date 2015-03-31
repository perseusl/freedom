<?php namespace AnyTV\Freedom;

class Config {

    protected $config;

    public function __construct()
    {
        $this->config = array(
            'application_name' => '',
            'client_id' => '665f627007666750b092f6a68396ed76',
            'client_secret' => '704a857f886341eb7980a899b18a2687',
            'redirect_uri' => '',

            //Do not change these values unless you're testing from different host
            'backend_host' => 'https://api.freedom.tm',
            'auth_host' => 'https://api.freedom.tm:3000',
            'basic_scopes' => 'web.view,mobile.view,self.view,self.edit,self.delete',
            'basic_roles' => ['all', 'recruiter', 'payout'],
            'roles' =>
                [
                    'all' => [
                            'self.view', 'self.edit', 'self.delete',
                            'self.logout', 'self.view_activities',
                            'self.view_points', 'self.add_points',
                            ],
                    'recruiter' => [
                            'recruiter.view', 'recruiter.earnings',
                            'prospect.find', 'prospect.add',
                            'prospect.view', 'prospect.update',
                            'prospect.delete', 'recruiter.join_network',
                            ],
                    'partner' => [
                            'channel.add', 'channel.view',
                            'channel.switch_network', 'channel.delete',
                            'channels.leaderboard', 'partner.upgrade_to_network',
                            'partner.earnings', 'partner.view_network_feed',
                            ],
                    'network' => [
                            'network.accept', 'network.edit',
                            'network.view', 'network.unpartner',
                            'network.approve_share', 'network.get_share',
                            'user.view', 'network.feed.add',
                            'network.feed.delete', 'network.recruits',
                            'network.spotlight',
                            ],
                    'admin' => [
                            'admin.edit_all', 'admin.create_all',
                            'admin.delete_all', 'admin.view_all',
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
    }

    public function get($key)
    {
        return $this->config[$key];
    }

    public function getBasicScopes()
    {
        return $this->config['basic_scopes'];
    }

    public function getScopesByRole($roles = [])
    {
        $scopes = [];
        if (sizeof($roles) === 0 ) {
            $roles = $this->config['basic_roles'];
        }

        foreach ($roles as $role) {
            $scopes = array_merge($scopes, $this->config['roles'][$role]);
        }

        return $scopes;
    }

    public function setApplicationName($name)
    {
        $this->config['application_name'] = $name;
    }

    public function setClientId($id)
    {
        if ($id === '' || $id === null) {
            throw new Exception ('Client ID can\'t be null');
        }

        $this->config['client_id'] = $id;
    }

    public function setClientSecret($secret)
    {
        if ($secret === '' || $secret === null) {
            throw new Exception ('Client Secret can\'t be null');
        }

        $this->config['client_secret'] = $secret;
    }

    public function getBasePath()
    {
        return $this->config['backend_host'];
    }

    public function getAuthPath()
    {
        return $this->config['auth_host'];
    }
}
