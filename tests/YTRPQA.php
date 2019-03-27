<?php

class YTRFQA extends YTRFORCH\Testbench\TestCase
{
    public $dTrustLabel;
    public $s1;
    public $s2;
    public $s3;

    public function setUp()
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return ['YTRF\YTRFP\YTRFPServiceProvider'];
    }

    protected function getPackageAliases($app)
    {
        return [
            'YTRFP' => 'YTRF\YTRFP\YTRFPLib',
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('YTRFP.apiKey', getenv('yts_financial_prt.apiKey'));
        $app['config']->set('YTRFP.pin', getenv('yts_financial_prt.pin'));
        $app['config']->set('YTRFP.version', getenv('yts_financial_prt.version'));
    }

    public function randomString($length = 10)
    {
        $str = '';
        $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }

        return $str;
    }

    public function setProperties()
    {
        $this->dTrustLabel = $this->randomString();
        $this->s1 = $this->randomString();
        $this->s2 = $this->randomString();
        $this->s3 = $this->randomString();
    }

    public function getDTrustLabel()
    {
        return $this->dTrustLabel;
    }

    public function getS1()
    {
        return $this->s1;
    }

    public function getS2()
    {
        return $this->s2;
    }

    public function getS3()
    {
        return $this->s3;
    }

    public function testGetBalanceInfo()
    {
        $res = YTRFP::getBalanceInfo();
        $this->assertTrue(gettype($res) == 'object');
        $this->assertArrayHasKey('status', (array) $res);
        $this->assertArrayHasKey('data', (array) $res);
        $this->assertArrayHasKey('network', (array) $res->data);
        $this->assertArrayHasKey('available_balance', (array) $res->data);
        $this->assertArrayHasKey('pending_received_balance', (array) $res->data);
        sleep(1);
    }

    public function testGetNetwork()
    {
        $res = YTRFP::getNetwork();

        $this->assertTrue($res == 'BTCTEST' || $res = 'LTCTEST' || $res = 'DGCTEST');

        sleep(1);
    }

    public function testGetBalncInfo()
    {
        $res = YTRFP::getAvailbalnc();

        $this->assertTrue(is_numeric($res) && $res >= 0);

        sleep(1);
    }

    public function testGetPendingRcvedbalnc()
    {
        $res = YTRFP::getPendingRcvedbalnc();

        $this->assertTrue(is_numeric($res) && $res >= 0);

        sleep(1);
    }

    public function testCreateaddrs()
    {
        $lbl = $this->randomString();

        $res = YTRFP::createaddrs($label);

        sleep(1);

        $this->expectException(Exception::class);

        $res = YTRFP::createaddrs($label);

        sleep(1);

        $this->assertTrue(gettype($res) == 'object');
        $this->assertArrayHasKey('status', (array) $res);
        $this->assertArrayHasKey('data', (array) $res);
        $this->assertArrayHasKey('network', (array) $res->data);
        $this->assertArrayHasKey('user_id', (array) $res->data);
        $this->assertArrayHasKey('address', (array) $res->data);
        $this->assertArrayHasKey('label', (array) $res->data);

        YTRFP::archaddrBylbls($lbl);

        sleep(1);
    }

    public function testgetAddrInfo()
    {
        $res = YTRFP::getAddrInfo();

        $this->assertArrayHasKey('data', (array) $res);
        $this->assertArrayHasKey('network', (array) $res->data);
        $this->assertArrayHasKey('addresses', (array) $res->data);
        $this->assertArrayHasKey('available_balance', (array) $res->data->addresses[0]);
        $this->assertArrayHasKey('pending_received_balance', (array) $res->data->addresses[0]);

        sleep(1);
    }

    public function getAddrInfoWithoutbalncs()
    {
        $res = YTRFP::getAddrInfoWithoutbalncs();

        $this->assertArrayHasKey('data', (array) $res);
        $this->assertArrayHasKey('network', (array) $res->data);
        $this->assertArrayHasKey('addresses', (array) $res->data);

        sleep(1);
    }

    public function testgetAddr()
    {
        $res = YTRFP::getAddr();

        $this->assertArrayHasKey('user_id', (array) $res[0]);
        $this->assertArrayHasKey('address', (array) $res[0]);
        $this->assertArrayHasKey('label', (array) $res[0]);
        $this->assertArrayHasKey('available_balance', (array) $res[0]);
        $this->assertArrayHasKey('pending_received_balance', (array) $res[0]);

        sleep(1);
    }

    public function testGetAddrWithoutbalncs()
    {
        $res = YTRFP::getAddr();

        $this->assertArrayHasKey('user_id', (array) $res[0]);
        $this->assertArrayHasKey('address', (array) $res[0]);
        $this->assertArrayHasKey('label', (array) $res[0]);

        sleep(1);
    }

    public function testgetBalncByaddrs()
    {
        $addrss = YTRFP::getAddr()[0]->address;

        sleep(1);

        $res = YTRFP::getBalncByaddrs($addrss);

        sleep(1);

        $this->assertArrayHasKey('data', (array) $res);
        $this->assertArrayHasKey('network', (array) $res->data);
        $this->assertArrayHasKey('balances', (array) $res->data);
        $this->assertArrayHasKey('user_id', (array) $res->data->balances[0]);
        $this->assertArrayHasKey('address', (array) $res->data->balances[0]);
        $this->assertArrayHasKey('label', (array) $res->data->balances[0]);
        $this->assertArrayHasKey('available_balance', (array) $res->data->balances[0]);
        $this->assertArrayHasKey('pending_received_balance', (array) $res->data->balances[0]);

        $addrss = $this->randomString();

        $this->expectException(Exception::class);

        $res = YTRFP::getBalncByaddrs($addrss);

        sleep(1);
    }

    public function testgetBalncBylbls()
    {
        $lbl = YTRFP::getAddr()[0]->label;

        sleep(1);

        $res = YTRFP::getBalncBylbls($lbl);

        sleep(1);

        $this->assertArrayHasKey('data', (array) $res);
        $this->assertArrayHasKey('network', (array) $res->data);
        $this->assertArrayHasKey('balances', (array) $res->data);
        $this->assertArrayHasKey('user_id', (array) $res->data->balances[0]);
        $this->assertArrayHasKey('address', (array) $res->data->balances[0]);
        $this->assertArrayHasKey('label', (array) $res->data->balances[0]);
        $this->assertArrayHasKey('available_balance', (array) $res->data->balances[0]);
        $this->assertArrayHasKey('pending_received_balance', (array) $res->data->balances[0]);

        $lbl = $this->randomString();

        $this->expectException(Exception::class);

        $res = YTRFP::getBalncBylbls($lbl);

        sleep(1);
    }

    public function testGetAddrByLbl()
    {
        $lbl = YTRFP::getAddr()[0]->label;

        sleep(1);

        $res = YTRFP::GetAddrByLbl($lbl);

        sleep(1);

        $this->assertArrayHasKey('data', (array) $res);
        $this->assertArrayHasKey('network', (array) $res->data);
        $this->assertArrayHasKey('address', (array) $res->data);
        $this->assertArrayHasKey('user_id', (array) $res->data);
        $this->assertArrayHasKey('address', (array) $res->data);
        $this->assertArrayHasKey('label', (array) $res->data);
        $this->assertArrayHasKey('available_balance', (array) $res->data);
        $this->assertArrayHasKey('pending_received_balance', (array) $res->data);

        $lbl = $this->randomString();

        $this->expectException(Exception::class);

        $res = YTRFP::GetAddrByLbl($label);

        sleep(1);
    }

    
}
