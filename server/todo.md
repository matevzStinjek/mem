
NEXT PHASE:
* middleware auth

PHASE + 1:
* split up Application, Application extends App
* censor github settings history
* blob <> assetcontroller <> foldercontent relationship
* usersgroups <> foldersMembership <> folder
* UserGroupController.php && Resource

PHASE + 2:
* rename UserGroup to UsersGroup and UserGroupMemberships to UsersGroupsMemberships
* facebook login integration
* rest of the fucking api
* fetch blobs of thumbnail/parameter size (?size=300x200) (aws lambda)
* salt password and hash with sha256 not sha512
* remove unneeded packages like doctrine/annotations
* services to daemons
* notification socket server
