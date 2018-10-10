# KBM API

KBM stands for Knowledge Base Manager and is a project for Webmaster class at University. The project is divided in two parts. The first one is a [Client Web Application](https://github.com/bymavc/kbm-web) and the second one is this API.

### Concept
The concept of this application could be described like "A GitHub only for documents" and it works like this.

- The System has Users.
- Users have Knowledge Bases.
- Knowledge Bases can be Public or Private.
- Public Knowledge Bases can be seen by anyone, Private Knowledge Bases can only be seen by authorized Users.
- Knowledge Bases can have Folders and Documents inside.
- Folders can also have Folders and Documents inside.
- Documents are units of useful information.
- A Document can have from none to many Tags that describe its content.
- Knowledge Bases can have Members, Workers and an Owner.
- Members can see the Knowledge Base content even when it is a Private Knowledge Base.
- Workers can edit the Knowledge Base content.
- Owners can give memberships of the Knowledge Base to other Users, edit Knowledge Base Content, Change Knowledge Base privacy policies and give the Knowledge Base ownership to other User.

### Structure
This Server Application offers an API to its Client, receiving requests in JSON format and responding accordingly in the same JSON format. The responses are Exception Driven so that the final user will always get a descriptive response while the server is active. 

This is a REST API so that it cannot handle sessions yet it handles authentication using tokens.

### Technologies, Frameworks, Libraries and Languages Involved

#### Technologies
- MySQL

#### Languages
- PHP 5

#### Libraries
- [Dompdf](https://github.com/dompdf/dompdf)
