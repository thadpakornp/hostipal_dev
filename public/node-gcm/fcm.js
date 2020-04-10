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
    data: { key1: 'msg1' }
});

var regTokens = ['ca6XGgYCSAE:APA91bH_aGyQ43H19-0UGjX7CV1AfSshBdyo8oDUPjtFCd9FGavqCNv5hfg5pVXrg1K1ALKHkm0ZKy1EiahfCwM15Ktd90fy2UCL48Zo0I8700DQUDMzrZI8eEKakOd6AXfiPXr26Pr-'];

sender.send(message, { registrationTokens: regTokens }, function (err, response) {
    if (err) console.error(err);
    else console.log(response);
});
