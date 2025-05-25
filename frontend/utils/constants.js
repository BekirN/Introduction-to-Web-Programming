
let Constants = {
    PROJECT_BASE_URL: (location.hostname === "localhost" || location.hostname === "127.0.0.1")
        ? "http://localhost/BekirNokic/Introduction-to-Web-Programming/backend/"
        : "https://your-production-server.com/backend/",
    USER_ROLE: "user",
    ADMIN_ROLE: "admin"
}
