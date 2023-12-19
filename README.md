<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>API DOC</title>
    <style>
        .uppercase{
            text-transform: uppercase;
        }

        body{
            font-family: Urbanist, Roboto, sans-serif;
            background-color: white;
            color: black;
        }

        .blocOfCode{
            display: flex;
            flex-direction: column;
            margin: 10px;
        }

        .jsonBody{
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #1e1e1e;
            padding: 20px;
            flex-direction: column;
        }

        .code{
            background-color: #252525;
            padding: 30px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-around;
        }

        .codeTitle{
            background-color: white;
            color: black;
            width: fit-content;
            max-width: 25%;
            border-radius: 15px;
            padding: 10px;
            text-transform: lowercase;
        }

        code{
            width: fit-content;
            padding: 5px;
            background-color: slategrey;
        }

        .button{
            padding: 10px;
            width: fit-content;
            border-radius: 25px;
            text-transform: uppercase;
            color: black;
        }

        .example{
            background-color: blue;
        }

        .get{
            background-color: darkgreen;
        }

        .post{
            background-color: #b7910d;
        }

        .put{
            background-color: dodgerblue;
        }

        .delete{
            background-color: indianred;
        }

        .log{
            padding: 10px;
            width: fit-content;
            border-radius: 25px;
            background-color: coral;
        }
    </style>

</head>
<body>
<div class="container">
    <div class="content">
        default path: <pre style="">partiel.thibautstachnick.com/api</pre>

        <h3>Example</h3>
        <div class="blocOfCode">
            <div class="code">
                <p class="codeTitle">Describe what the url does</p>
                <code>link to the url</code>
                <div class="button example">method</div>
                <div class="log">Condition (show only if required)</div>
            </div>
            <div class="jsonBody"><span>Example</span><span>{ <br> "json":true,<br> "api":"easy"<br> }</span></div>
        </div>

        <h2 class="uppercase">users</h2>
        <div class="blocOfCode">
            <div class="code">
                <p class="codeTitle">Register as a new user of the application</p>
                <code>https://partiel.thibautstachnick.com/register</code>
                <div class="button post">post</div>
            </div>
            <div class="jsonBody"><span>User registration</span><span>{ <br> "username":"{your Username}", <br> "password":"{your Password}" <br> }</span></div>
        </div>

        <div class="blocOfCode">
            <div class="code">
                <p class="codeTitle">Login and get a token from the application</p>
                <code>/login_check</code>
                <div class="button post">post</div>
            </div>
            <div class="jsonBody"><span>User logging in</span><span>{ <br> "username":"{your Username}", <br> "password":"{your Password}" <br> }</span></div>
        </div>

        <div class="blocOfCode">
            <div class="code">
                <p class="codeTitle">show all users from the application and their ids</p>
                <code>/showUsers</code>
                <div class="button get">get</div>
                <div class="log">loggedIn</div>
            </div>
        </div>

        <div class="blocOfCode">
            <div class="code">
                <p class="codeTitle">show all invitations from current user</p>
                <code>/invitations/index</code>
                <div class="button get">get</div>
                <div class="log">loggedIn</div>
            </div>
        </div>

        <div class="blocOfCode">
            <div class="code">
                <p class="codeTitle">accept an invitation for a private Event</p>
                <code>/invitations/accept/{invitation Id}</code>
                <div class="button put">put</div>
                <div class="log">loggedIn</div>
            </div>
        </div>

        <div class="blocOfCode">
            <div class="code">
                <p class="codeTitle">deny an invitation for a private Event</p>
                <code>/invitations/deny/{invitation Id}</code>
                <div class="button put">put</div>
                <div class="log">loggedIn</div>
            </div>
        </div>





        <h2 class="uppercase">EVENTS</h2>
        <h3 class="uppercase">public events</h3>
        <div class="blocOfCode">
            <div class="code">
                <p class="codeTitle">Show all the public events</p>
                <code>/public/event/index</code>
                <div class="button get">get</div>
                <div class="log">loggedIn</div>
            </div>
        </div>

        <div class="blocOfCode">
            <div class="code">
                <p class="codeTitle">Attend to a public event with it ID</p>
                <code>/public/event/attend/{EventId}</code>
                <div class="button get">get</div>
                <div class="log">loggedIn</div>
            </div>
        </div>

        <div class="blocOfCode">
            <div class="code">
                <p class="codeTitle">Create a new public event</p>
                <code>/public/event/create</code>
                <div class="button post">post</div>
                <div class="log">loggedIn</div>
            </div>
            <div class="jsonBody"><span>Public event creation</span><span>{ <br> "place":"string",<br>
    "description":"event description",<br>
    "startOn": "date | format (d-m-Y)",<br>
    "endOn":"date | format (d-m-Y)",<br>
    "isPlacePrivate":true/false <br> }</span></div>
        </div>

        <div class="blocOfCode">
            <div class="code">
                <p class="codeTitle">Get all participants from a public event</p>
                <code>/public/event/getParticipants/{EventId}</code>
                <div class="button get">get</div>
                <div class="log">loggedIn</div>
            </div>
        </div>

        <h3 class="uppercase">private event</h3>
        <div class="blocOfCode">
            <div class="code">
                <p class="codeTitle">Show all the private events since you're participating</p>
                <code>/private/event/index</code>
                <div class="button get">get</div>
                <div class="log">loggedIn</div>
            </div>
        </div>

        <div class="blocOfCode">
            <div class="code">
                <p class="codeTitle">Create a new private event</p>
                <code>/private/event/create</code>
                <div class="button post">post</div>
                <div class="log">loggedIn</div>
            </div>
            <div class="jsonBody"><span>Private event creation</span><span>{ <br> "place":"string",<br>
    "description":"event description",<br>
    "startOn": "date | format (d-m-Y)",<br>
    "endOn":"date | format (d-m-Y)",<br>
    "isPlacePrivate":true/false <br> }</span></div>
        </div>

        <div class="blocOfCode">
            <div class="code">
                <p class="codeTitle">Invite a user to a private event</p>
                <code>/private/event/invite/{EventID}/{Invited User Id}</code>
                <div class="button post">post</div>
                <div class="log">loggedIn</div>
            </div>
        </div>

        <div class="blocOfCode">
            <div class="code">
                <p class="codeTitle">Cancel or schedule again an existing event</p>
                <code>/private/event/changeStatus/{EventID}</code>
                <div class="button put">put</div>
                <div class="log">loggedIn</div>
            </div>
        </div>

        <div class="blocOfCode">
            <div class="code">
                <p class="codeTitle">Edit an existing private event</p>
                <code>/private/event/edit/{eventID}</code>
                <div class="button put">put</div>
                <div class="log">loggedIn</div>
            </div>
                <div class="jsonBody"><span>Private event edition</span><span>{ <br> "place":"string",<br>
        "description":"event description",<br>
        "startOn": "date | format (d-m-Y)",<br>
        "endOn":"date | format (d-m-Y)",<br>
        "isPlacePrivate":true/false <br> }</span></div>
            </div>
        </div>

    <h3 class="uppercase">contributions | linked to private event on private place only</h3>

    <div class="blocOfCode">
        <div class="code">
            <p class="codeTitle">Show all contributions include suggestions and standalone supports</p>
            <code>/private/event/showContributions/{Event ID}</code>
            <div class="button get">get</div>
            <div class="log">loggedIn</div>
        </div>
    </div>

    <div class="blocOfCode">
        <div class="code">
            <p class="codeTitle">Show all suggestions from the host of an event</p>
            <code>/private/event/showSuggestions/{Event ID}</code>
            <div class="button get">get</div>
            <div class="log">loggedIn</div>
        </div>
    </div>

    <div class="blocOfCode">
        <div class="code">
            <p class="codeTitle">add a suggestion if you're the host of the event</p>
            <code>/private/event/addSuggestion/{Event ID}</code>
            <div class="button post">post</div>
            <div class="log">loggedIn</div>
        </div>
        <div class="jsonBody"><span>Add a suggestion to private event</span><span>{<br> "title":"{your suggestion title}" <br>}</span></div>
    </div>

    <div class="blocOfCode">
        <div class="code">
            <p class="codeTitle">Start supporting a suggestion to make it a support into the contributions</p>
            <code>/private/event/supportSuggestion/{Suggestion ID}</code>
            <div class="button put">put</div>
            <div class="log">loggedIn</div>
        </div>
    </div>

    <div class="blocOfCode">
        <div class="code">
            <p class="codeTitle">Stop supporting a suggestion to remove it from the contributions and make it available again</p>
            <code>/private/event/supportSuggestion/{Suggestion ID}</code>
            <div class="button put">put</div>
            <div class="log">loggedIn</div>
        </div>
    </div>

    <div class="blocOfCode">
        <div class="code">
            <p class="codeTitle">add a support not linked to a suggestion if you're participating to private event</p>
            <code>/private/event/addSupport/{Event ID}</code>
            <div class="button post">post</div>
            <div class="log">loggedIn</div>
        </div>
        <div class="jsonBody"><span>Add a support to private event</span><span>{<br> "title":"{your support title}" <br>}</span></div>
    </div>

    <div class="blocOfCode">
        <div class="code">
            <p class="codeTitle">Delete a support if you're the creator of it or the private event host</p>
            <code>/private/event/removeSupport/{Support ID}</code>
            <div class="button delete">delete</div>
            <div class="log">loggedIn</div>
        </div>
    </div>


    </div>

</body>
</html>
