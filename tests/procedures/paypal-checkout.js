var casper = require('casper').create({
    verbose: true,
    logLevel: 'debug'
    // pageSettings: {
    //     userAgent: "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36"
    //         + " (KHTML, like Gecko) Chrome/53.0.2785.89 Safari/537.36"
    // }
});
const timeout = 60000;

casper.on('remote.message', function(msg) {
    this.echo('remote message: ' + msg);
});

casper.on('page.error', function(msg) {
    this.echo('page error: ' + msg, 'ERROR');
});

function screenShot() {
    this.captureSelector(
        './storage/test/' + (new Date()).getTime() + '.png',
        'html'
    );
}

/**
 * @param {string} selector
 */
function waitForSelector(selector) {
    casper.then(function () {
        console.log('Waiting for selector ' + selector);
        casper.waitForSelector(
            selector,
            function loginToPayPal() {
                console.log('Found selector ' + selector);
                screenShot.call(this);
            },
            function loginFailed() {
                screenShot.call(this);
                this.die('Waited too long for selector ' + selector, 1);
            },
            timeout
        );
    })
}

if (!casper.cli.options['start'] || casper.cli.options['start'].length < 5) {
    casper.die('No start URL provided');
}
casper.start(casper.cli.options['start']).viewport(1024, 768);

waitForSelector('form[name="login_form"]');

casper.then(function loginToPayPal() {
    screenShot.call(this);
    this.fillSelectors(
        'form[name="login_form"]',
        {
            'input#email': casper.cli.options['email'],
            'input#password': casper.cli.options['password']
        },
        true
    );
});

waitForSelector('#confirmButtonTop');

casper.then(function clickContinue() {
    screenShot.call(this);
    this.click('#confirmButtonTop');
});

casper.then(function finish() {
    screenShot.call(this);
    console.log('final url: ' + this.getCurrentUrl());
});

casper.run();
