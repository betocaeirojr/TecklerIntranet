#!/bin/bash

clear


echo "###############################################################################################"
echo "###############################################################################################"
echo "##                                                                                           ##"
echo "## Welcome to Teckler Scripts for Metrics Dashboard System                                   ##"
echo "## Author: Alberto Augusto Caeiro Jr                                                         ##"
echo "## Created on 2013-11-28                                                                     ##"
echo "##                                                                                           ##"
echo "###############################################################################################"
echo "###############################################################################################"

echo "Today is " `date`

# DEVELOPMENT CONFIG
BASE_URL="http://localhost/teckler/intranet/metrics"
cd /Library/WebServer/Documents/teckler/intranet/metrics

#PRODUCTION CONFIG
#BASE_URL="http://localhost/intranet/metrics"
#cd /var/www/html/intranet/metrics


#ACTIVE PROFILES
echo "############################################################################################"
echo "[ControlFlow] - Starting Script for #ACTIVE PROFILES metrics"
echo "[CURL_Call] - cURL "$BASE_URL"/view_MetricsDashboard_ActiveProfiles.php"
curl $BASE_URL/view_MetricsDashboard_ActiveProfiles.php > View_MetricsDashboard_ActiveProfiles.html
echo ""


#AGING PAGEVIEWS
echo "############################################################################################"
echo "[ControlFlow] - Starting Script for #AGING PAGEVIEWS metrics"
echo "[CURL_Call] - cURL "$BASE_URL"/view_MetricsDashboard_Aging.php"
curl $BASE_URL/view_MetricsDashboard_Aging.php > View_MetricsDashboard_AgingPageviews.html
echo ""

#CONTACT IMPORTING & INVITATION SENT
echo "############################################################################################"
echo "[ControlFlow] - Starting Script for #CONTACT IMPORTS & INVITATIONS SENT metrics"
echo "[CURL_Call] - cURL "$BASE_URL"/view_MetricsDashboard_InvitationsSent.php"
curl $BASE_URL/view_MetricsDashboard_InvitationsSent.php > View_MetricsDashboard_InvitationsSent.html
echo ""


#REMAINING CONSOLIDATED METRICS
echo "############################################################################################"
echo "############################################################################################"
echo "[ControlFlow] - Starting Script for #CONTACT IMPORTS & INVITATIONS SENT metrics"
echo "[CURL_Call] - cURL "$BASE_URL"/view_MetricsDashboard_Consolidated.php"
curl $BASE_URL/view_MetricsDashboard_Consolidated.php > View_MetricsDashboard_Consolidated.html
echo ""


