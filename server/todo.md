NOW:
think about client structure, routes (split vue apps wise), figure out how to share npm packages etc
serve static files & endpoints for them
rethink structure, i guess (folder structue wise)

THIS PHASE:
discriminator pattern unclaimed vs registered user
create visible/searchable folder query from userId
add foldermembership via folderController
controllers for UserGroup
test folders with reg and unreg user

NEXT PHASE:
* server side cookie sessions
* check s3 again, blobcontroller
* blob <> assetcontroller <> foldercontent relationship
* UserGroupController.php && Resource
* facebook login integration

PHASE + 1:
* censor github settings history
* s3 stream video
* fetch blobs of thumbnail/parameter size (?size=300x200) (aws lambda)
* remove unneeded packages like doctrine/annotations
* services to daemons
* notification socket server
* create phantom accounts with 3rd party ids, schema: id email facebookId googleId isClaimed
* captcha for registering & lock login after 3 attempts
* garbage collector for sessions, blobs