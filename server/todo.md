NEXT PHASE:
* upgrade to slim 4
* refactor dependencies to $app (extend SlimApp to my App :) )s
* filter chain type auth
* custom Request wrapper ($request = new Request(SlimRequest $slimRequest);) that adds user(auth), params, em
* filter in routes (middleware)

PHASE + 1:
* usersgroups <> foldersMembership <> folder
* blob <> assetcontroller <> foldercontent relationship
* UserGroupController.php && Resource

PHASE + 2:
* rename UserGroup to UsersGroup and UserGroupMemberships to UsersGroupsMemberships
* advanced permissions
* rest of the fucking api
* facebook login integration
* dependencies --> Application.php
* fetch blobs of thumbnail/parameter size (?size=300x200) (aws lambda)
* salt password and hash with sha256 not sha512