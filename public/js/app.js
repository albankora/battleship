(function ($) {
    'use strict';

    var baseUrl = function () {
        var pathArray = location.href.split('/');
        return pathArray[0] + '//' + pathArray[2];
    };

    var ajaxRequest = function (options) {
        $.ajax({
            url: baseUrl() + options.url,
            type: options.type,
            data: options.data,
            success: function (data) {
                responseHandler(data);
            }
        });
    };

    var responseHandler = function (response) {
        if (response.data !== undefined) {
            switch (response.data.action) {
                case 'new':
                    newGame();
                    break;
                case 'init':
                    initGame();
                    break;
                case 'data':
                    handleData(response.data);
                    break;
                case 'miss':
                    handleMiss(response.data);
                    showMessage(response.data);
                    break;
                case 'hit':
                    handleHit(response.data);
                    showMessage(response.data);
                    break;
                case 'finish':
                    handleFinish(response.data);
                    showMessage(response.data);
                    break;
            }
        }
    };

    var handleFinish = function(data){
        var elements = $('.' + data.item + ' i' );
        elements.removeClass('fa-circle');
        elements.addClass('fa-close');
    };

    var showMessage = function(data){
        $('.msg').text(data.message);
    };

    var handleHit = function (data) {
        var elements = $('.' + data.item + ' i' );
        elements.removeClass('fa-circle');
        elements.addClass('fa-close');
    };

    var handleMiss = function (data) {
        var elements = $('.' + data.item + ' i' );
        elements.removeClass('fa-circle');
        elements.addClass('fa-minus');
    };

    var handleData = function (data) {

        if (data.item.length == 0) {
            initGame();
        }

        var count = data.item.length;

        for(var i = 0; i < count; i++){
            if(data.item[i].result == 'miss'){
                handleMiss(data.item[i]);
            }else{
                handleHit(data.item[i]);
            }
        }
    };

    var initGame = function () {
        var elements = $('td i');
        elements.removeClass('fa-minus');
        elements.removeClass('fa-close');
        elements.addClass('fa-circle');
    };

    var getGameData = function () {
        ajaxRequest({
            type: 'GET',
            url: '/gameData'
        });
    };

    var newGame = function () {
        ajaxRequest({
            type: 'GET',
            url: '/newGame'
        });
    };

    var sendCoords = function (coord) {
        ajaxRequest({
            type: 'POST',
            url: '/shot',
            data: {'shot': coord}
        });
    };

    $(document).on("submit", ".form", function (e) {
        e.preventDefault();
        var coord = $('.coord').val();
        sendCoords(coord);
        return false;
    });

    getGameData();

})(jQuery);