(function() {
    var chat = {
        messageToSend: '',
        messageResponses: [],
        init: function() {
            this.cacheDOM();
            this.bindEvents();
            this.render();
            this.fetchPeople(); // Ajoutez cette ligne
        },
        cacheDOM: function() {
            this.$chatHistory = $('.chat-history');
            this.$button = $('button');
            this.$textarea = $('#message-to-send');
            this.$chatHistoryList = this.$chatHistory.find('ul');
        },
        bindEvents: function() {
            this.$button.on('click', this.addMessage.bind(this));
            this.$textarea.on('keyup', this.addMessageEnter.bind(this));
        },
        fetchPeople: function() {
            $.ajax({
                url: 'chat.php',
                method: 'GET',
                success: function(data) {
                    var people = JSON.parse(data);
                    var peopleList = $('#people-list .list');
                    peopleList.empty();
                    for (var i = 0; i < people.length; i++) {
                        // Create a new list item for each person
                        var listItem = $('<li class="clearfix"></li>');
                        // Create a div for the person's name (in this case, the user ID)
                        var nameDiv = $('<div class="name"></div>').text(people[i]);
                        // Append the name div to the list item
                        listItem.append(nameDiv);
                        // Append the list item to the people list
                        peopleList.append(listItem);
                    }
                }
            });
        },
        render: function() {
            this.scrollToBottom();
            if (this.messageToSend.trim() !== '') {
                var template = Handlebars.compile($("#message-template").html());
                var context = {
                    messageOutput: this.messageToSend,
                    time: this.getCurrentTime()
                };

                this.$chatHistoryList.append(template(context));
                this.scrollToBottom();
                this.$textarea.val('');

                // Post the message to the server
                $.post('send_message.php', {message: this.messageToSend});

                // Fetch response from the server
                $.get('fetch_messages.php', function(data) {
                    var messages = JSON.parse(data);
                    chat.messageResponses = messages;
                    chat.displayResponse();
                });
            }
        },
        addMessage: function() {
            this.messageToSend = this.$textarea.val();
            this.render();
        },
        addMessageEnter: function(event) {
            // enter was pressed
            if (event.keyCode === 13) {
                this.addMessage();
            }
        },
        scrollToBottom: function() {
            this.$chatHistory.scrollTop(this.$chatHistory[0].scrollHeight);
        },
        getCurrentTime: function() {
            return new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        },
        displayResponse: function() {
            for (var i = 0; i < this.messageResponses.length; i++) {
                var templateResponse = Handlebars.compile($("#message-response-template").html());
                var contextResponse = {
                    response: this.messageResponses[i].message,
                    time: this.messageResponses[i].time
                };

                this.$chatHistoryList.append(templateResponse(contextResponse));
                this.scrollToBottom();
            }
        }
    };

    $(document).ready(function() {
        // Charger les utilisateurs avec qui l'utilisateur a parlé
        function loadUsers() {
            $.ajax({
                type: "GET",
                url: "fetch_users_talked_to.php",
                success: function(response) {
                    var users = JSON.parse(response);
                    var userList = $('.list');
                    userList.empty();

                    users.forEach(function(user) {
                        var userItem = '<li class="clearfix" data-id="' + user.id + '">'
                            + '<div class="about">'
                            + '<div class="status">'
                            + '<i class="fa fa-circle offline"></i>'
                            + '<div class="name">' + user.prenom + ' ' + user.nom + '</div>'
                            + '</div>'
                            + '</div>'
                            + '</li>';
                        userList.append(userItem);
                    });

                    // Ajouter un écouteur d'événements pour charger les messages lors du clic sur un utilisateur
                    $('.list li').click(function() {
                        var destinataire_id = $(this).data('id');
                        $('.list li').removeClass('active');
                        $(this).addClass('active');
                        loadMessages(destinataire_id);
                    });
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        // Charger les messages
        function loadMessages(destinataire_id) {
            $.ajax({
                type: "POST",
                url: "fetch_message.php",
                data: {
                    cible_id: destinataire_id
                },
                success: function(response) {
                    var messages = JSON.parse(response);
                    var chatHistoryList = $('.chat-history ul');
                    chatHistoryList.empty();

                    messages.forEach(function(message) {
                        var messageTemplate = Handlebars.compile($("#message-template").html());
                        var messageHtml = messageTemplate({
                            time: message.date_envoi,
                            messageOutput: message.contenu
                        });
                        chatHistoryList.append(messageHtml);
                    });
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        // Envoyer un message
        $('.chat-message button').click(function() {
            var message = $('#message-to-send').val();
            var destinataire_id = $('.list li.active').data('id'); // Utilisateur actuellement sélectionné

            if (!destinataire_id || !message) {
                return;
            }

            $.ajax({
                type: "POST",
                url: "send_message.php",
                data: {
                    message: message,
                    destinataire_id: destinataire_id,
                    type_message: 1 // ou autre type de message si nécessaire
                },
                success: function(response) {
                    console.log(response);
                    $('#message-to-send').val('');
                    loadMessages(destinataire_id);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        // Charger les utilisateurs et les messages initiaux
        loadUsers();
    });




    chat.init();
})();
