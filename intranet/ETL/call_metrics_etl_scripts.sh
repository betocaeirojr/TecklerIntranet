#!/bin/bash

clear


echo "###############################################################################################"
echo "###############################################################################################"
echo "##                                                                                           ##"
echo "## Welcome to Teckler ETL Scripts for Metrics System                                         ##"
echo "## Author: Alberto Augusto Caeiro Jr                                                         ##"
echo "## Created on 2013-07-19                                                                     ##"
echo "##                                                                                           ##"
echo "###############################################################################################"
echo "###############################################################################################"

echo "Today is " `date`

#cd ~/temp-etl
# DEVELOPMENT CONFIG
#BASE_URL="http://localhost/teckler/intranet/ETL"
#PRODUCTION CONFIG
BASE_URL="http://localhost/intranet/metrics/etl-scripts"

echo "Using "$BASE_URL " as the Base URL for CURL calling" 

if [ $# -eq 1 ]
   then 
   		DATESTRING=$1
		REFDAY=$1
		echo "[ControlFlow] - Input Date Provided, using "$1 " as DateStringInput..."
   else
      	DATESTRING=`date +"%Y%m%d"`
      	DAY=`date +"%Y%m%d" | cut -c7-8`
		MONTH=`date +"%Y%m%d" | cut -c5-6`
		YEAR=`date +"%Y%m%d" | cut -c1-4`
		LAST2DIGITYEAR=`date +"%Y %m %d" | cut -c3-4`
      	echo "[Debug] - No Date as Parameter or Parameter Input error, using "$DATESTRING " as DateStringInput..."

		# Computing Reference Day. Always using DATESTRING - 1day
		DAY=`date +"%Y%m%d" | cut -c7-8`
		MONTH=`date +"%Y%m%d" | cut -c5-6`
		YEAR=`date +"%Y%m%d" | cut -c1-4`
		LAST2DIGITYEAR=`date +"%Y %m %d" | cut -c3-4`
		ISLEAPYEAR=0

		#Jan, Mar, May, Jul, Ago, Out, Dec = 31 days
		#Abr, Jun, Set, Nov = 30 days
		#Feb = 28 or 29 Days

		TEMP=$(($YEAR % 4))
		#echo $TEMP

		if [ "$TEMP" -gt "0" ] 
		   then ISLEAPYEAR=0
		else if [ "$LAST2DIGITYEAR" -eq "00" ]
			then ISLEAPYEAR=FALSE
		     else ISLEAPYEAR=1
		     fi
		fi 
		#echo "Is this year a Leap Year: "$ISLEAPYEAR

		MONTHDAY=$MONTH$DAY #echo "[Debug] - Month-Day is: "$MONTHDAY
		TODAY=$YEAR-$MONTH-$DAY #echo "[Debug] - Today is: "$TODAY
		YESTERDAY=$(($DATESTRING-1)) #echo "[Debug] - Yesterday was: "$YESTERDAY

		# Calculating Reference Day
		REFDAY=$YESTERDAY
		#Checking if Jan 1st
		if [ "$MONTHDAY" -eq "0101" ] 
			then
			REFDAY=$(($YEAR-1))"1231"
		#	echo "Reference Day Is: "$REFDAY
		fi

		#Checking if Feb 1st
		if [ "$MONTHDAY" -eq "0201" ] 
			then
			REFDAY=$YEAR"0131"
		#	echo "Reference Day Is: "$REFDAY
		fi

		#Checking if Mar 1st
		if [ "$MONTHDAY" -eq "0301" ] 
			then
			if [ "$ISLEAPYEAR" -eq "0" ]
				then
					REFDAY=$YEAR"0228"
				else
					REFDAY=$YEAR"0229"
			fi
		#	echo "Reference Day Is: "$REFDAY
		fi

		#Checking if Abr 1st
		if [ "$MONTHDAY" -eq "0401" ] 
			then
			REFDAY=$YEAR"0331"
		#	echo "Reference Day Is: "$REFDAY
		fi

		#Checking if May 1st
		if [ "$MONTHDAY" -eq "0501" ] 
			then
			REFDAY=$YEAR"0430"
		#	echo "Reference Day Is: "$REFDAY
		fi

		#Checking if Jun 1st
		if [ "$MONTHDAY" -eq "0601" ] 
			then
			REFDAY=$YEAR"0531"
		#	echo "Reference Day Is: "$REFDAY
		fi

		#Checking if Jul 1st
		if [ "$MONTHDAY" -eq "0701" ] 
			then
			REFDAY=$YEAR"0630"
		#	echo "Reference Day Is: "$REFDAY
		fi

		#Checking if Ago 1st
		if [ "$MONTHDAY" -eq "0801" ] 
			then
			REFDAY=$YEAR"0731"
		#	echo "Reference Day Is: "$REFDAY
		fi

		#Checking if Set 1st
		if [ "$MONTHDAY" -eq "0901" ] 
			then
			REFDAY=$YEAR"0831"
		#	echo "Reference Day Is: "$REFDAY
		fi

		#Checking if Out 1st
		if [ "$MONTHDAY" -eq "1001" ] 
			then
			REFDAY=$YEAR"0930"
		#	echo "Reference Day Is: "$REFDAY
		fi

		#Checking if Nov 1st
		if [ "$MONTHDAY" -eq "1101" ] 
			then
			REFDAY=$YEAR"1031"
		#	echo "Reference Day Is: "$REFDAY
		fi

		#Checking if Nov 1st
		if [ "$MONTHDAY" -eq "1201" ] 
			then
			REFDAY=$YEAR"1130"
		#	echo "Reference Day Is: "$REFDAY
		fi
fi

echo "[ControlFlow] - ETL Reference Day is: "$REFDAY	
echo "[ControlFlow] - Starting to run ETL Scripts for each metric." 

#USER & PROFILES METRICS
echo "############################################################################################"
echo "[ControlFlow] - Starting ETL Scripts for USER/PROFILE metrics"
echo "[CURL_Call] - cURL "$BASE_URL"/populate_users_metrics.php?day=$REFDAY"
curl $BASE_URL/populate_users_metrics.php?day=$REFDAY > users_out_$REFDAY.out
#cat users_out_$REFDAY.out
cat users_out_$REFDAY.out | grep "Success" || grep "Failed"
echo ""

#TECK METRICS
echo "############################################################################################"
echo "[ControlFlow] - Starting ETL Scripts for TECKS metrics"
echo "[CURL_Call] - "$BASE_URL"/populate_tecks_metrics.php?day=$REFDAY"
curl $BASE_URL/populate_tecks_metrics.php?day=$REFDAY > tecks_out_$REFDAY.out
#cat tecks_out_$REFDAY.out
cat tecks_out_$REFDAY.out | grep "Success" || grep "Failed"
echo ""

#SHARE METRICS
echo "############################################################################################"
echo "[ControlFlow] - Starting ETL Scripts for SHARES metrics"
echo "[CURL_Call] - "$BASE_URL"/populate_shares_metrics.php?day=$REFDAY"
echo "[Info] - Shares Metrics has no Delta, always fetching info To Date"
curl $BASE_URL/populate_shares_metrics.php?day=$REFDAY > shares_out_$REFDAY.out
#cat shares_out_$REFDAY.out
cat shares_out_$REFDAY.out | grep "Success" || grep "Failed"
echo ""

#LIKES METRICS
echo "############################################################################################"
echo "[ControlFlow] - Starting ETL Scripts for LIKE metrics"
echo "[CURL_Call] - "$BASE_URL"/populate_likes_metrics.php?day=$REFDAY"
echo "[Info] - Likes Metrics has no Delta, always fetching info To Date"
curl $BASE_URL/populate_likes_metrics.php?day=$REFDAY > likes_out_$REFDAY.out
#cat likes_out_$REFDAY.out
cat likes_out_$REFDAY.out | grep "Success" || grep "Failed"
echo ""

#FOLLOW METRICS
echo "############################################################################################"
echo "[ControlFlow] - Starting ETL Scripts for FOLLOW metrics"
echo "[CURL_Call] - "$BASE_URL"/populate_follow_metrics.php?day=$REFDAY"
echo "[Info] - Follows Metrics has no Delta, always fetching info To Date"
curl $BASE_URL/populate_follow_metrics.php?day=$REFDAY > follow_out_$REFDAY.out
#cat follow_out_$REFDAY.out
cat follow_out_$REFDAY.out | grep "Success" || grep "Failed"
echo ""

#ONLINE USERS (1DAY) METRICS
echo "############################################################################################"
echo "[ControlFlow] - Starting ETL Scripts for ONLINE metrics"
echo "[CURL_Call] - "$BASE_URL"/populate_online_metrics.php?day=$REFDAY"
echo "[Info] - Online Users Metrics has no Delta, always fetching info To Date"
curl $BASE_URL/populate_online_metrics.php?day=$REFDAY > online_out_$REFDAY.out
#cat online_out_$REFDAY.out
cat online_out_$REFDAY.out | grep "Success" || grep "Failed"
echo ""

#PAGEVIEWS METRICS
echo "############################################################################################"
echo "[ControlFlow] - Starting ETL Scripts for PAGEVIEWS metrics"
echo "[CURL_Call] - "$BASE_URL"/populate_pageviews_metrics.php?day=$REFDAY"
curl $BASE_URL/populate_pageviews_metrics.php?day=$REFDAY > pageviews_out_$REFDAY.out
#cat pageviews_out_$REFDAY.out
cat pageviews_out_$REFDAY.out | grep "Succeed" || grep "Failed"
echo ""

#AVERAGE PAGEVIEWS METRICS
echo "############################################################################################"
echo "[ControlFlow] - Starting ETL Scripts for AVERAGE PAGEVIEWS metrics"
echo "[CURL_Call] - "$BASE_URL"/populate_average_pageviews_metrics.php?day=$REFDAY"
curl $BASE_URL/populate_average_pageviews_metrics.php?day=$REFDAY > average_pageviews_out_$REFDAY.out
#cat pageviews_out_$REFDAY.out
cat average_pageviews_out_$REFDAY.out | grep "Succeed" || grep "Failed"
echo ""


#REVENUE METRICAS
echo "############################################################################################"
echo "[ControlFlow] - Starting ETL Scripts for REVENUE metrics"
echo "[CURL_Call] - "$BASE_URL"/populate_revenue_metrics.php?day=$REFDAY"
curl $BASE_URL/populate_revenue_metrics.php?day=$REFDAY > revenue_out_$REFDAY.out
#cat revenue_out_$REFDAY.out
cat revenue_out_$REFDAY.out | grep "Succeed" || grep "Failed"
echo ""

#ALEXA RANKING METRICS
echo "############################################################################################"
echo "[ControlFlow] - Starting ETL Scripts for ALEXA RANKING metrics"
echo "[CURL_Call] - "$BASE_URL"/populate_alexa_ranking_metrics.php"
echo "[Info] - Alexa Ranking Metrics has no Delta, always fetching info To Date"
curl $BASE_URL/populate_alexa_ranking_metrics.php?day=$REFDAY > alexarank_out_$REFDAY.out
#cat alexarank_out_$REFDAY.out
cat alexarank_out_$REFDAY.out | grep "Succeed" || grep "Failed"
echo ""

#GOOGLE ANALYTICS METRICS
echo "############################################################################################"
echo "[ControlFlow] - Starting ETL Scripts for GOOGLE ANALYTICS metrics"
echo "[CURL_Call] - "$BASE_URL"/populate_ga_metrics.php"
echo "[Info] - Google Analytics Metrics has only Delta"
curl $BASE_URL/populate_ga_metrics.php > google-analytics_out_$REFDAY.out
#cat google-analytics_out_$REFDAY.out
cat google-analytics_out_$REFDAY.out | grep "Succeed" || grep "Failed"
echo ""

#CONTACT IMPORTS AND INVITATIONS SENT
echo "############################################################################################"
echo "[ControlFlow] - Starting ETL Scripts for CONTACTS IMPORTS and INVITATIONS SENT metrics"
echo "[CURL_Call] - "$BASE_URL"/populate_contacts_invitations_metrics.php"
echo "[Info] - Contact Imported and Invitations Sent Metrics has only Delta"
curl $BASE_URL/populate_contacts_invitations_metrics.php?day=$REFDAY > contacts_imported_invitations_sent_out_$REFDAY.out
#cat google-analytics_out_$REFDAY.out
cat google-contacts_imported_invitations_sent_out_$REFDAY.out | grep "Succeed" || grep "Failed"
echo ""


echo "############################################################################################"
echo "[ControlFlow] - Finishing...."
echo "[Info] - Compressing and cleaning audit generated output files..."
tar czf metrics-etl_$DATESTRING-$REFDAY.tar.gz *.out
rm *.out

echo "[Info] - Copying to Backup S3 Bucket ..."
#sudo -s `mv *.gz /mnt/tk_bk/`
echo ""

echo "############################################################################################"
echo "[ControlFlow] - ELT Script Finished. Goodbye!"
echo "############################################################################################"
echo ""
