const httpServer = require('http').createServer()
const io = require("socket.io")(httpServer, {
    allowEIO3: true,
    cors: {
        origin: "http://localhost:8081",
        //TAES
        //origin: "https://fabiogaspar11.github.io",
        methods: ["GET", "POST"],
        credentials: true
    }
})
httpServer.listen(8080, function () {
    console.log('listening on *:8080')
})
io.on('connection', function (socket) {
    console.log(`client ${socket.id} has connected`)

    socket.on('logged_in', function (username, userType) {
        if (userType == 'A') {
            socket.join("administrators")
            console.log(`Room Administrator ${username} join`)
        }

        else{
            socket.join(username)
            console.log(`Room Users ${username} join`)

        }
    })

    socket.on('logged_out', function (username, userType) {
        console.log(`Room User ${username} leave`)
        if (userType == 'A')
            socket.leave("administrators")
        else
            socket.leave(username)
    })

    socket.on('newTransaction', function (transaction, username) {
        io.to(username).emit('newTransaction', transaction, username)
    })

    socket.on('toggleVcardStatus', function (status, username) {
        io.to(username).emit('toggleVcardStatus', status, username)
    })

    socket.on('userDeleted', function (username) {
        io.to(username).emit('userDeleted', username)
    })

    socket.on('changesAdminDefaultCategories', function () {
        io.to("administrators").emit('changesAdminDefaultCategories')
    })

    socket.on('changesListOfVcards', function () {
        console.log("OLA")
        io.to("administrators").emit('changesListOfVcards')
    })

    socket.on('changesListOfAdmins', function (operation,email) {
        io.to("administrators").emit('changesListOfAdmins',operation,email)
    })

})