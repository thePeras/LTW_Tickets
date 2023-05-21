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
    FOREIGN KEY(user) REFERENCES Clients(username)
);

CREATE TABLE Agents(
    username TEXT PRIMARY KEY,
    FOREIGN KEY (username) REFERENCES Clients(username)
);

CREATE TABLE Admins(
    username TEXT PRIMARY KEY,
    FOREIGN KEY (username) REFERENCES Agents(username)
);

CREATE TABLE FAQs(
    id INTEGER PRIMARY KEY NOT NULL,
    createdByUser TEXT NOT NULL,
    title TEXT NOT NULL,
    content TEXT NOT NULL,

    FOREIGN KEY (createdByUser) REFERENCES Agents(username)
);

CREATE TABLE Departments(
    name TEXT PRIMARY KEY,
    description TEXT
);

CREATE TABLE AgentDepartments(
    agent TEXT NOT NULL,
    department TEXT NOT NULL,

    PRIMARY KEY (agent, department),
    FOREIGN KEY (agent) REFERENCES Agents(username),
    FOREIGN KEY (department) REFERENCES Departments(name)
);

CREATE TABLE Tickets(
    id INTEGER PRIMARY KEY,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    status TEXT,
    hashtags TEXT,
    assignee TEXT,
    department TEXT,
    faq INTEGER,
    createdByUser TEXT NOT NULL,
    createdAt NUMBER NOT NULL,

    FOREIGN KEY (assignee) REFERENCES Agents(username),
    FOREIGN KEY (createdByUser) REFERENCES Clients(username),
    FOREIGN KEY (department) REFERENCES Departments(name),
    FOREIGN KEY (faq) REFERENCES FAQs(id)
);

CREATE TABLE Comments(
    id INTEGER PRIMARY KEY,
    content TEXT NOT NULL,
    createdByUser TEXT NOT NULL,
    createdAt NUMBER NOT NULL,
    ticket INTEGER NOT NULL,
    FOREIGN KEY (createdByUser) REFERENCES  Clients(username),
    FOREIGN KEY (ticket) REFERENCES Tickets(id)
);

CREATE TABLE Changes(
    id INTEGER PRIMARY KEY,
    timestamp INTEGER NOT NULL,
    user TEXT NOT NULL,
    FOREIGN KEY (user) REFERENCES Clients(username)
);

CREATE TABLE TicketsChanges(
    change NUMBER NOT NULL,
    ticket NUMBER NOT NULL,
    PRIMARY KEY (change, ticket),
    FOREIGN KEY (change) REFERENCES Changes(id),
    FOREIGN KEY (ticket) REFERENCES Tickets(id)
);

CREATE TABLE AssignedChanges(
    agent TEXT,
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