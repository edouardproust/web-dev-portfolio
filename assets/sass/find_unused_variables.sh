# How to use:
# Let’s imagine you have folder “sass” where are SCSS files of your project. 
# Then:
# 1. Create file "find_unused_variables.sh" in the parent folder of "sass"
# 2. Copy and paste content from solution script to it
# 3. Open the parent folder of "sass" in your terminal
# 4. Run the following command to do this file executable: ```bash chmod +x ./find_unused_variables.sh ```
# 5. Run the script: ```bash ./find_unused_variables.sh . ```
# 6. As result you will have in the shell list of the unused SCSS variables

VAR_NAME_CHARS='A-Za-z0-9_-'

find "$1" -type f -name "*.scss" -exec grep -o "\$[$VAR_NAME_CHARS]*" {} ';' | sort | uniq -u