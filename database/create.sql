pragma foreign_keys=on;

DROP TABLE IF EXISTS FAQs;
DROP TABLE IF EXISTS Admins;
DROP TABLE IF EXISTS StatusChanges;
DROP TABLE IF EXISTS AssignedChanges;
DROP TABLE IF EXISTS Changes;
DROP TABLE IF EXISTS AgentDepartments;
DROP TABLE IF EXISTS Agents;
DROP TABLE IF EXISTS Departments;
DROP TABLE IF EXISTS Comments;
DROP TABLE IF EXISTS Tickets;
DROP TABLE IF EXISTS Sessions;
DROP TABLE IF EXISTS Clients;



CREATE TABLE Clients(
    username TEXT PRIMARY KEY,
    email TEXT NOT NULL,
    password TEXT NOT NULL,
    displayName TEXT NOT NULL,
    image TEXT NOT NULL,
    createdAt INTEGER NOT NULL,
    passwordInvalidated INTEGER DEFAULT 0
);

CREATE TABLE Sessions(
    user TEXT PRIMARY KEY,
    token TEXT UNIQUE NOT NULL,
    lastUsedDate INTEGER NOT NULL,
    FOREIGN KEY(user) REFERENCES Clients(username) ON DELETE CASCADE
);

CREATE TABLE Agents(
    username TEXT PRIMARY KEY,
    FOREIGN KEY (username) REFERENCES Clients(username) ON DELETE CASCADE
);

CREATE TABLE Admins(
    username TEXT PRIMARY KEY,
    FOREIGN KEY (username) REFERENCES Agents(username) ON DELETE CASCADE
);

CREATE TABLE FAQs(
    id INTEGER PRIMARY KEY NOT NULL,
    createdByUser TEXT,
    title TEXT NOT NULL,
    content TEXT NOT NULL,

    FOREIGN KEY (createdByUser) REFERENCES Agents(username) ON DELETE SET NULL
);

CREATE TABLE Departments(
    name TEXT PRIMARY KEY,
    description TEXT
);

CREATE TABLE AgentDepartments(
    agent TEXT NOT NULL,
    department TEXT NOT NULL,

    PRIMARY KEY (agent, department),
    FOREIGN KEY (agent) REFERENCES Agents(username) ON DELETE CASCADE,
    FOREIGN KEY (department) REFERENCES Departments(name) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Tickets(
    id INTEGER PRIMARY KEY,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    status TEXT,
    hashtags TEXT,
    assignee TEXT,
    createdByUser TEXT,
    department TEXT,

    FOREIGN KEY (assignee) REFERENCES Agents(username) ON DELETE SET NULL,
    FOREIGN KEY (createdByUser) REFERENCES Clients(username) ON DELETE SET NULL,
    FOREIGN KEY (department) REFERENCES Departments(name) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE Comments(
    id INTEGER PRIMARY KEY,
    content TEXT NOT NULL,
    createdByUser TEXT,
    ticket INTEGER NOT NULL,
    FOREIGN KEY (createdByUser) REFERENCES  Clients(username) ON DELETE SET NULL,
    FOREIGN KEY (ticket) REFERENCES Tickets(id) ON DELETE CASCADE
);

CREATE TABLE Changes(
    id INTEGER PRIMARY KEY,
    timestamp INTEGER NOT NULL,
    user TEXT,
    FOREIGN KEY (user) REFERENCES Clients(username) ON DELETE SET NULL

);

CREATE TABLE AssignedChanges(
    agent TEXT NOT NULL,
    change INTEGER NOT NULL,
    PRIMARY KEY (change, agent),
    FOREIGN KEY (agent) REFERENCES Agents(username),
    FOREIGN KEY (change) REFERENCES Changes(id)
);

CREATE TABLE StatusChanges(
    status TEXT NOT NULL,
    change INTEGER PRIMARY KEY,
    FOREIGN KEY (change) REFERENCES Changes(id)
);