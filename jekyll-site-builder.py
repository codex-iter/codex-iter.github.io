import os

def jekyll_builder(cwd):
    #asks for build no
    #asks for commit message
    #creates a ../temp folder
    #builds site inside that folder

    build_no = input("Enter the build number\t")
    comm_message = input("Describe your changes\t")
    
    print("\n * Creating temp dir at "+cwd+"/../temp/")
    os.system("mkdir "+cwd+"/../temp/")

    print("\n * Building static pages to "+cwd+"/../temp/" + "\n-------------------------")
    os.system("bundle exec jekyll build -d ../temp/")
    print("\n * Building done......\n=======================================\n\n")

    repo_handler(build_no, comm_message)

def repo_handler(build_no, comm_message):
    #checks out to master branch
    #rm -rf everything inside
    #commits with build no and message and appends p1
    #copies stuff in ../temp to current dir
    #deletes _site/ folder if created
    #commits with build no and message and appends p2
    #pushes to github

    os.system("git checkout master")
    os.system("rm -rf ./*")
    print("\n * Removed files inside master branch......" + "\n-------------------------")
    os.system("git addcomm -m \""+build_no+" : "+comm_message+" | part 1\"")
    os.system("cp -r ../temp/* ./")
    print("\n * Added build files inside master branch......" + "\n-------------------------")
    os.system("git addcomm -m \""+build_no+" : "+comm_message+" | part 2\"")
    print("\n * Pushing to GitHub" + "\n-------------------------")
    os.system("git push origin master")


if __name__ == '__main__':
    cwd = os.getcwd()
    jekyll_builder(cwd)
