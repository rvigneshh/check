<?php
    require __DIR__.'/../CurlClient.php';

    use PHPUnit\Framework\TestCase;

    class CurlClientTest extends TestCase
    {
        private $curlclient;

        protected function setUp()
        {
            $this->curlclient = new Library\Core\CurlClient();
        }

        protected function tearDown()
        {
            $this->curlclient = null;
        }

        /**
         * has array of inputs for the testing
         * @return [array] [Array of inputs]
         */
        public function inputsConceptMastery()
        {
            return array(
                    array('stu_f49cda7df0'),
                    array('stu_0009717471'),
                    );
        }

        /**
         * Function to test the concept mastery with different inputs
         * @param  [string] $stu_id [Student id to be tested]
         * @test
         * @dataProvider inputsConceptMastery
         */
        public function check_conceptmastery($stu_id)
        {
            $method = "POST";
            $url = "conceptMastery/R/get.cm.class/print";
            $params = array('input' => array(
                                      "Anon_Student_Id" => $stu_id,
                                      "Subject" => "Algebra",
                                      "Concept" => "Unit DECIMAL-OPERATIONS-2" ));
            $params = json_encode($params);

            $result_json = $this->curlclient->exeCurl($url, $method, $params);
            $result_arr = json_decode($result_json);

            $this->assertTrue($result_arr->status);
            $this->assertContains($result_arr->val, array('Yes', 'No'));
        }

        /**
         * Function to test the retention with different inputs
         * @test
         */
        public function check_retention()
        {
            $curlclient = new Library\Core\CurlClient();

            $url = "retention/R/get.retention.score/json";

            $params = array('input' => array( 'Opportunity.SubSkills.' => 7, 'Opportunity.KTracedSkills.' => 7, 'Count.KTracedSkills.' => 1, 'Count.SubSkills.' => 1, 'Session.Start.gap.days' => 2, 'Step.Duration..sec.' => 30), 'model.file' => '/datadrive/dataset/retention_test.rds');

            $params = json_encode($params);

            $method = "POST";

            $result = $this->curlclient->exeCurl($url, $method, $params);
            $result = trim($result);
            $this->assertEquals($result, '[0.0384]');
        }
    }
