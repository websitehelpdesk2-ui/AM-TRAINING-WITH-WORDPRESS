/*
* sending data to Google Analytics
 */
function analyticsDataPush ( action, eventName = '', info = '' ) {
    if ( typeof dataLayer != "undefined" ) {
        dataLayer.push({
            event: '10web-event',
            'eventName': eventName,
            'eventAction': action,
            'info': info,
            'domain_id': twbb_helper.domain_id
        });
    }
}