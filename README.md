**Introduction:**
This yivi-disclosure repository is the frontend for disclosing [Yivi](https://yivi.app) attributes. For additional information about the uzi project, check the coordination repo at https://github.com/minvws/nl-rdo-uzi-coordination Make

**Get up and running:**
The easiest way to get the uzi project running is to run the "make setup" command from the coordination repository. This will automatically run the "make setup" of all project. You also have the option to run "make setup" command manually for each project.



Manual setup of this project
1. Set the npm token if you haven't done this already

```bash
TOKEN ?= $(shell bash -c 'read -p "github_token: " github_token; echo $$github_token')
echo "//npm.pkg.github.com/:_authToken=$(TOKEN)"  >> ~/.npmrc
```

2. Run ```make setup```
3. Run ```make run``` to start the php application

**Additional Information:**
- All functionality of this client will only work if all other uzi projects are also running including the docker environments.

**Conclusion:**
After the configurations above all functionality should be working and without changing configuration your client website should be available on localhost:8000.

# Technical overview
![UZI-inlogmiddelen flows-combination-flow-diagram](https://user-images.githubusercontent.com/12181969/229889972-aba96faf-34ba-4283-8c20-e9fcf558032f.png)


# Certificates
- Check the coordination repo for needed certificates and how to configure them.
Note: the make setup command in the coordination repo should do this automatically
