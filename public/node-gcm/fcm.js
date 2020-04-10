var gcm = require('node-gcm');

var sender = new gcm.Sender('AAAAn_kVz9E:APA91bE-lkAGE9sm6oPAP3aIHUkU3iZxRjjDQD4hQiQKtnqA6GuR2V0VeM-r6bO_MZNF-Jds93IIYk1LUdgHsGk-FL5VIH3fMPH0hpWuKBaDVt9xACzCJ6zInWt4tsIcv2IRKNqmNMef');

var message = new gcm.Message({
    contentAvailable: true,
    notification: {
        sound: 'default',
        title: "Hello, World",
        icon: "ic_launcher",
        body: "This is a notification that will be displayed if your app is in the background."
    },
    data: { key: '20' }
});

var regTokens = ['f35-603xXiQ:APA91bGXsWAez25K7JOb-KoN5LHuCOYIVqJq1sSVemug2qlEXbWZthH17PqO0JUCXQgH81xRSz87TnLOjOhXwY5Ln0NJ1dkczTjAkyzYUQj0GYRLH6Yib8dflwZwqGbFDQtB97Ws9owV'];

sender.send(message, { registrationTokens: regTokens }, function (err, response) {
    if (err) console.error(err);
    else console.log(response);
});
