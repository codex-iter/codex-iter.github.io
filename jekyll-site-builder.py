import os

def jekyll_builder(cwd):
    #first push to jekyll branch
    #asks for build no
    #asks for commit message
    #creates a ../temp folder
    #builds site inside that folder

    #os.system("git push origin jekyll")
    #print("\n * Pushed to jekyll branch" + "\n-------------------------\n-------------------------")

    build_no = input("Enter the build number\t")
    comm_message = input("Describe your changes\t")
    
    print("\n * Creating temp dir at "+cwd+"/../temp/" + "\n-------------------------")
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
    print("\n * Removed files inside master branch......" + "\n-------------------------\n\n")
    
    print("\n * Commiting changes......" + "\n-------------------------")
    os.system("git addcomm -m \"Build No. "+build_no+" :: "+comm_message+" | part 1\"")
    print("\n\n")
    
    os.system("cp -r ../temp/* ./")
    print("\n * Added build files inside master branch......" + "\n-------------------------\n\n")

    if(os.path.isdir("./_site/")):
        print("\n * _site/ folder exists, deleting the folder......" + "\n-------------------------")
        os.system("rm -rf ./_site")
        print("\n * _site/ deleted" + "\n-------------------------\n")
    else:
        print("\n * _site/ folder does not exist" + "\n-------------------------\n\n")

    print("\n * Commiting changes......" + "\n-------------------------")
    os.system("git addcomm -m \"Build No. "+build_no+" :: "+comm_message+" | part 2\"")
    print("\n\n")

    print("\n * Pushing to GitHub" + "\n-------------------------")
    os.system("git push origin master")

    if(os.path.isdir("./_site/")):
        print("\n * _site/ folder exists, deleting the folder......" + "\n-------------------------")
        os.system("rm -rf ./_site")
        print("\n * _site/ deleted" + "\n-------------------------\n")
    else:
        print("\n * _site/ folder does not exist" + "\n-------------------------\n\n")    

    print("\n * switching back to jekyll branch" + "\n-------------------------\n\n")
    os.system("git checkout jekyll")

if __name__ == '__main__':
    cwd = os.getcwd()
    jekyll_builder(cwd)
