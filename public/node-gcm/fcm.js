var gcm = require('node-gcm');

var sender = new gcm.Sender('AAAAn_kVz9E:APA91bE-lkAGE9sm6oPAP3aIHUkU3iZxRjjDQD4hQiQKtnqA6GuR2V0VeM-r6bO_MZNF-Jds93IIYk1LUdgHsGk-FL5VIH3fMPH0hpWuKBaDVt9xACzCJ6zInWt4tsIcv2IRKNqmNMef');

var message = new gcm.Message({
    contentAvailable: true,
    notification: {
        sound: 'default',
        title: "Hello, World",
        icon: "app_icon",
        body: "This is a notification that will be displayed if your app is in the background."
    },
    data: { key: '15' }
});

var regTokens = ['fd_AeYuFQUaouqo3f34xGy:APA91bEcsnGdmH_3wZvCtFH879IPBRqt0xR9pZYNwRJL6Wr2M-mdYOuQKQZYYimKIHkDqib7dVxcUqpjvL06EzKx75HwfcEy6lD3tMuvPvsECJLjSQodHrPvvZrAqBNfWN36L7boHr0v'];

sender.send(message, { registrationTokens: regTokens }, function (err, response) {
    if (err) console.error(err);
    else console.log(response);
});
