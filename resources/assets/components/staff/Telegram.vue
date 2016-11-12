window.addEventListener("load", function () {
    console.log("Starting Telegram Vue.");

    Vue.http.headers.common["Content-Type"] = "application/json";
    new Vue({
        el:      "#telegram",
        data:    {
            updates: []
        },
        mounted: function () {
            this.fetchUpdates();
            setInterval(function () {
                this.fetchUpdates();
            }.bind(this), 1000);
        },
        methods: {
            fetchUpdates: function () {
                this.$http.get("/staff/telegram").then(function (response) {
                    this.updates = response.body;
                });
            }
        },
        filters: {
            date: function (value) {
                var date = new Date(value * 1000);
                return date.getFullYear()
                    + "-" + date.getMonth()
                    + "-" + date.getDate()
                    + "@" + date.getHours()
                    + ":" + date.getMinutes()
                    + ":" + date.getSeconds();
            }
        }
    });

    console.log("Telegram Vue started.");
});
