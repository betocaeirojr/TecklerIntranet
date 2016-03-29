<?php

date_default_timezone_set("America/Sao_Paulo");

include("AmazonServices.php");

class MailTrackingDynamoDbStats {

    private $dynamoDb;
    private $amazon;

    function MailTrackingDynamoDbStats() {
        $this->amazon = new AmazonServices();
        $this->dynamoDb = $this->amazon->get_dynamo_db();
    }


    function getEmailTrackingInfo(){
        $iterator = $this->dynamoDb->getIterator('Scan', array(
                                                'TableName' => 'EMAIL_TRACKING',
                                                'ScanFilter' => array(
                                                    'EMAIL_CODE' => array(
                                                        'AttributeValueList' => array(
                                                            array('S' => 'Email')
                                                        ),
                                                    'ComparisonOperator' => 'CONTAINS'
                                                    ),
                                                ),
                                                'Limit' => 100
                                            )
                                        );

        // Each item will contain the attributes we added
        $result = $this->_convert_email_tracking_from_dynamo($iterator);
        return $result;
    }

    function getInvitationSentInfo(){
        $iterator = $this->dynamoDb->getIterator('Scan', array(
                                                'TableName' => 'USER_EMAIL_TRACKING',
                                                'Limit' => 1000)
                                        );

        // Each item will contain the attributes we added
        $rawResult = $this->_convert_invitation_sent_from_dynamo($iterator);

        // Assembling resulting matrix (consolidated by date)
        foreach ($rawResult as $ikey => $ivalue) {
            //echo "[DEBUG] ################################ <BR>\n";
            //echo "<PRE>";
            //print_r($ivalue);
            //echo "</PRE>";
            $dynamoDB_InviteDate    = $ivalue['EmailDate']; 
            if (isset($resultingArray[$dynamoDB_InviteDate])){
                $resultingArray[$dynamoDB_InviteDate]['EmailSent']  = $resultingArray[$dynamoDB_InviteDate]['EmailSent'] + $ivalue['EmailSent'];  
                $resultingArray[$dynamoDB_InviteDate]['EmailReads']  = $resultingArray[$dynamoDB_InviteDate]['EmailReads'] + $ivalue['EmailReads'];  
                $resultingArray[$dynamoDB_InviteDate]['EmailClicks'] = $resultingArray[$dynamoDB_InviteDate]['EmailClicks'] + $ivalue['EmailClicks'];  
            } else {
                $resultingArray[$dynamoDB_InviteDate]['EmailDate']  = $ivalue['EmailDate'];  
                $resultingArray[$dynamoDB_InviteDate]['EmailSent']  = $ivalue['EmailSent'];  
                $resultingArray[$dynamoDB_InviteDate]['EmailReads']  = $ivalue['EmailReads'];  
                $resultingArray[$dynamoDB_InviteDate]['EmailClicks'] = $ivalue['EmailClicks'];  
            }
        }

        arsort($resultingArray);

        //echo "[DEBUG] ############################################# <BR>\n";
        //echo "<PRE>";
        //print_r($resultArray);
        //echo "</PRE>";


        return $resultingArray;
    }

/*
    function getInvitationSentInfo(){
        $iterator = $this->dynamoDb->getIterator('Scan', array(
                                                'TableName' => 'USER_EMAIL_TRACKING',
                                                'Limit' => 1000)
                                        );

        // Each item will contain the attributes we added
        $rawResult = $this->_convert_invitation_sent_from_dynamo($iterator);
        arsort($rawResult);
        echo "[DEBUG] -- Raw Result from iterator... \n";
        echo "<PRE>";
        print_r($rawResult);
        echo "</PRE>";


        // Consolidating by date
        $numResults = count($rawResult);

        // Starting to assemble final structures for returning
        $i              = 0;
        $j              = 0;
        $date           = date("Y-m-d");
        $iSendAmmount   = 0;
        $iReadAmmount   = 0;
        $iClickAmmount  = 0;

        // Assembling resulting matrix (consolidated by date)
        while ($i<$numResults){
            echo    "[DEBUG] Date is: " . $date . 
                    ", type of " . gettype($date) ;
            echo    " || Email Date is : " . $rawResult[$i]['EmailDate']. 
                    ", type of " . gettype($rawResult[$i]['EmailDate']) . "<BR>\n";

            if ((string)$date == $rawResult[$i]['EmailDate'] ) {
                echo "[DEBUG] -- Entenring IF BRANCH... <BR><BR>\n";
                $iSendAmmount  = $iSendAmmount  + $rawResult[$i]['EmailSent'];
                $iReadAmmount  = $iReadAmmount  + $rawResult[$i]['EmailReads'];
                $iClickAmmount = $iClickAmmount + $rawResult[$i]['EmailClicks'];
            } else {
                echo "[DEBUG] -- Entenring ELSE BRANCH... <BR><BR>\n";
                // Assemble the final array
                if ($j>0) {
                    $InvitationsSent[$j] = array(
                        "Date"          => $date,
                        "TotalSent"     => $iSendAmmount,
                        "TotalRead"     => $iReadAmmount,
                        "TotalClicked"  => $iClickAmmount
                        );
                    }
                $date           = $rawResult[$i]['EmailDate'];
                $iSendAmmount   = $rawResult[$i]['EmailSent'];
                $iReadAmmount   = $rawResult[$i]['EmailReads'];
                $iClickAmmount  = $rawResult[$i]['EmailClicks'];
                $j++;
            }
            $i++;
        }
        return $InvitationsSent;
    }
*/

    private function _convert_email_tracking_from_dynamo($dynamoTracking) {
        

        foreach ($dynamoTracking as $item) {
            
            //$emailTrackingDetail_EMAIL_CODE = $item['EMAIL_CODE']['S'];  
            $findUnderline = stripos($item['EMAIL_CODE']['S'], "_");  

            if ($findUnderline==FALSE) {
                $email = explode("-",$item['EMAIL_CODE']['S']);
                
                // Extracting the Email Type
                $emailTrackingDetail_EmailType = $email[0];

                // Extracting the Email Date
                $strDate = $email[1] . "-" . $email[2] . "-" . $email[3] ;
                $emailTrackingDetail_EmailDate = date('Y-m-d', strtotime($strDate));

            } else {
                $email = explode("_",$item['EMAIL_CODE']['S']);
                
                // Extracting the Email Type
                $emailTrackingDetail_EmailType = $email[0];

                // Extracting the Email Date
                $strDate = $email[1];
                $emailTrackingDetail_EmailDate = date('Y-m-d', strtotime($strDate));

            }

            // Extracting Tracking Metrics of Read and Click
            $emailTrackingDetail_READ_AMOUNT = $item['READ_AMOUNT']['N'];
            $emailTrackingDetail_CLICK_AMOUNT = $item['CLICK_AMOUNT']['N']; 
            $emailTrackingDetail_SEND_AMOUNT = (!empty($item['SEND_AMOUNT']['N']) ? $item['SEND_AMOUNT']['N'] : 0 );

            $emailTrackingDetail[] = array(
                "EmailDate"     => "$emailTrackingDetail_EmailDate",
                "EmailType"     => "$emailTrackingDetail_EmailType",
                "EmailSent"     => "$emailTrackingDetail_SEND_AMOUNT",
                "EmailReads"    => "$emailTrackingDetail_READ_AMOUNT",
                "EmailClicks"   => "$emailTrackingDetail_CLICK_AMOUNT"
                );

            // print_r($emailTrackingDetail);
        }
        
        return (!empty($emailTrackingDetail) ? $emailTrackingDetail : "" ) ;
    }

    private function _convert_invitation_sent_from_dynamo($dynamoTracking) {
        

        foreach ($dynamoTracking as $item) {
            
            // Find Parts
            $lineParts = explode("_",$item['EMAIL_CODE']['S']);
            $partsSize = count($lineParts);

            // Extracting Date Part
            $completeDatePart = explode("-", $lineParts[$partsSize-1]);
            $strDatePart = $completeDatePart[0] . "-" . $completeDatePart[1] . "-" . $completeDatePart[2];
            $lineItemEmailDate = date('Y-m-d', strtotime($strDatePart));


            // Assembling Final Information
            $invitationInfo_SentDate = $lineItemEmailDate;

            // Extracting Tracking Metrics of Read and Click
            $invitationInfo_READ_AMOUNT = $item['READ_AMOUNT']['N'];
            $invitationInfo_CLICK_AMOUNT = $item['CLICK_AMOUNT']['N']; 
            $invitationInfo_SEND_AMOUNT = (!empty($item['SEND_AMOUNT']['N']) ? $item['SEND_AMOUNT']['N'] : 0 );

            $emailInvitationSentDetail[] = array(
                "EmailDate"     => "$invitationInfo_SentDate",
                "EmailSent"     => "$invitationInfo_SEND_AMOUNT",
                "EmailReads"    => "$invitationInfo_READ_AMOUNT",
                "EmailClicks"   => "$invitationInfo_CLICK_AMOUNT"
                );

            // print_r($emailTrackingDetail);
        }
        arsort($emailInvitationSentDetail);

        return (!empty($emailInvitationSentDetail) ? $emailInvitationSentDetail : "" ) ;
    }
    



}

?>