
#pip install python-wordpress-xmlrpc
#pip install tableauserverclient
#pip install colorama
from wordpress_xmlrpc import Client, WordPressPost, WordPressPage, WordPressTerm
from wordpress_xmlrpc.methods.posts import GetPosts, NewPost
from wordpress_xmlrpc.methods.users import GetUserInfo
from wordpress_xmlrpc.methods import posts, taxonomies, media
from wordpress_xmlrpc.compat import xmlrpc_client
import tableauserverclient as TSC
from colorama import init
from colorama import Fore, Back, Style
import os

init()
init(autoreset=True)

def cls():
    os.system('cls' if os.name=='nt' else 'clear')

# IDs for all parent pages
parentPages = {
    'Planning & Accountability': 752,
    'Strong Start': 5637,
    'CA Dashboard': 4311,

    'Academics': 4157,
    'Grades': 1977,
    'Illuminate Assessments': 758,
    'MAP - All Seasons': 736,
    'Fall MAP': 1820,
    'Spring MAP': 785,
    'Winter MAP': 733,
    'Reading': 761,
    'SBAC - Public': 1857,
    'SBAC - KLA': 1842,

    'Attendance & Behavior': 3923,
    'Behavior': 3437,
    'Attendance': 3926,

    'Enrollment': 1894,
    'Attrition': 1932,
    'Demographics': 721,
    'Recruitment & Enrollment': 718,

    'Student Groups & Programs': 3433,
    'After School Program': 5542,
    'After School': 5542,
    'EL': 743,
    'SPED': 4076,

    'Surveys': 1916,
    'Family': 773,
    'TNTP': 767,
    'Student': 770,

    'KTC': 782,

    'Big KIPPsters': 4263,
    'HR': 3440,
}

# authentication to tableau server
tableau_auth = TSC.TableauAuth('kippndc\josgonzalez', 'Lakai219', 'KIPPLA')
# tableau server
server = TSC.Server('https://stg-tableau.kipp.org/', use_server_version=True)
# authorization for wordpress
client = Client('https://kastle.kippla.org/xmlrpc.php',
                'Jose Test', 'lakai219')
# create wordpress page to be added
page = WordPressPage()
fileDir = os.path.dirname(os.path.realpath('__file__'))
print (fileDir)

workbooks = []
views = []
category = None
category2 = None
isWorkbook = False
isView = False
user_wb = None
user_v = None
owner = None

with server.auth.sign_in(tableau_auth):
    req_options = TSC.RequestOptions()
    req_options.page_size(1000)
    all_workbooks, pagination_item = server.workbooks.get(req_options)
    all_views, pagination_item = server.views.get()

    cls()
    print (Fore.YELLOW + '\n\n\n\n\nWorkbooks')
    for workbook in all_workbooks:
        if workbook.project_name == 'KASTLE':
            print (workbook.name)
            workbooks.append(workbook.name)

    print ('')

    while (not isWorkbook):
        # print wb names for user to copy
        print (Fore.CYAN + 'Enter workbook of view to add:')
        user_wb = input('')
        if (user_wb in workbooks):
            isWorkbook = True
            ownerid = workbook.owner_id
            wb_arr = user_wb.split('_')
            if len(wb_arr) == 2:
                category = wb_arr[0]
            elif len(wb_arr) == 3:
                category = wb_arr[1]
                category2 = wb_arr[0]
        else:
            print (Fore.WHITE + Back.RED + 'Workbook does not exist\n')

    cls()
    print (Fore.YELLOW + '\nViews in ' + user_wb)
    for workbook in all_workbooks:
        if (workbook.name == user_wb):
            server.workbooks.populate_views(workbook)
            owner = server.users.get_by_id(workbook.owner_id)
            for view in workbook.views:
                # print view names for user to copy
                views.append(view)
                print (view.name)

    while (not isView):
        print (Fore.CYAN + '\nEnter view to add or enter "all" to enter all views:')
        user_v = input('')
        if (user_v == 'all'):
            for view in views:
                isView = True
                server.views.populate_preview_image(view)
                # windows
                #image_path = 'View Thumbnails//' + user_wb.replace('/', ' ') + '_' + view.name.replace('/', ' ') + '.jpg'
                # mac
                image_path = 'View Thumbnails/' + user_wb.replace('/', ' ') + '_' + view.name.replace('/', ' ') + '.jpg'
                filepath = os.path.join(fileDir, image_path)

                with open(filepath, "wb") as image_file:
                    image_file.write(view.preview_image)

        else:
            for view in views:
                if (user_v == view.name):
                    isView = True
                    server.views.populate_preview_image(view)
                    # windows
                    #image_path = 'View Thumbnails//' + user_wb.replace('/', ' ') + '_' + user_v.replace('/', ' ') + '.jpg'
                    # mac
                    image_path = 'View Thumbnails/' + user_wb.replace('/', ' ') + '_' + user_v.replace('/', ' ')+ '.jpg'
                    filepath = os.path.join(fileDir, image_path)

                    with open(filepath, "wb") as image_file:
                        image_file.write(view.preview_image)

            if (not isView):
                print (Fore.WHITE + Back.RED + 'View does not exist\n')

    if (user_v == 'all'):
        print (Fore.YELLOW + '\nInsert all views in ' + user_wb + '? (Y/N)')
    else:
        print (Fore.YELLOW + '\nInsert ' + user_v +
               ' in the ' + category + ' category? (Y/N)')
    user_confirm = input('')
    cls()
    if (user_confirm == 'y' or user_confirm == 'Y' or user_confirm == 'yes'):
        print('Adding Pages...')
        if (user_v == 'all'):
            for view in views:
                user_v = view.name
                # add wordpress page info
                # get screenshot saved on computer and upload to wordpress
                # windows
                #image_path = 'View Thumbnails//' + user_wb.replace('/', ' ') + '_' + user_v.replace('/', ' ') + '.jpg'
                # mac
                image_path = 'View Thumbnails/' + user_wb.replace('/', ' ') + '_' + user_v.replace('/', ' ') + '.jpg'
                filename = os.path.join(fileDir, image_path)
                data = {
                    'name': user_wb + '_' + user_v + '.jpg',
                    'type': 'image/jpg',
                }
                with open(filename, 'rb') as img:
                    data['bits'] = xmlrpc_client.Binary(img.read())
                response = client.call(media.UploadFile(data))
                thumbnail_id = response['id']

                page.title = user_v
                page.parent_id = parentPages[category]
                if (category2):
                    cat = category2
                else:
                    cat = category

                page.excerpt = ''

                page.terms_names = {
                    'category': [cat],
                    'owner': [owner.fullname],
                }

                page.custom_fields = []
                page.custom_fields.append({
                    'key': 'dashboard_link', 'value': ''
                })
                page.custom_fields.append({
                    'key': 'dashboard_tips', 'value': ''
                })
                page.custom_fields.append({
                    'key': 'context', 'value': ''
                })
                page.custom_fields.append({
                    'key': 'dashboard_link', 'value': "<script type='text/javascript' src='https://tableau.kipp.org/javascripts/api/viz_v1.js'></script><div class='tableauPlaceholder' style='width: 1200px; height: 100px;'><object class='tableauViz' width='1200' height='1000' style='display:none;'><param name='host_url' value='https%3A%2F%2Ftableau.kipp.org%2F' /> <param name='embed_code_version' value='3' /> <param name='site_root' value='&#47;t&#47;KIPPLA' /><param name='name' value='" + user_wb.replace(' ', '').replace('&', '').replace('/', '').replace('(', '').replace(')', '') + "&#47;" + user_v.replace(' ', '').replace('&', '').replace('/', '').replace('(', '').replace(')', '') + "' /><param name='tabs' value='no' /><param name='toolbar' value='yes' /><param name='showAppBanner' value='false' /></object></div>"
                })
                page.custom_fields.append({
                    'key': 'staging_dashboard_link', 'value': "<script type='text/javascript' src='https://stg-tableau.kipp.org/javascripts/api/viz_v1.js'></script><div class='tableauPlaceholder' style='width: 1200px; height: 100px;'><object class='tableauViz' width='1200' height='1000' style='display:none;'><param name='host_url' value='https%3A%2F%2Fstg-tableau.kipp.org%2F' /> <param name='embed_code_version' value='3' /> <param name='site_root' value='&#47;t&#47;KIPPLA' /><param name='name' value='" + user_wb.replace(' ', '').replace('&', '').replace('/', '').replace('(', '').replace(')', '') + "&#47;" + user_v.replace(' ', '').replace('&', '').replace('/', '').replace('(', '').replace(')', '') + "' /><param name='tabs' value='no' /><param name='toolbar' value='yes' /><param name='showAppBanner' value='false' /></object></div>"
                })

                # thumbnail id of the image we just uploaded
                page.thumbnail = thumbnail_id
                # publish page
                page.post_status = 'publish'
                # create page
                page.id = client.call(posts.NewPost(page))
            
                print (Fore.RED + '\nAdded ' + category + ': ' + view.name + '\n' +
                       'Owner: ' + owner.fullname + '\n')
        else:
            # add wordpress page info
            # get screenshot saved on computer and upload to wordpress
            # windows
            #image_path = 'View Thumbnails//' + user_wb+ '_' + user_v + '.jpg'
            # mac
            image_path = 'View Thumbnails/' + user_wb + '_' + user_v + '.jpg'
            filename = os.path.join(fileDir, image_path)
            data = {
                'name': user_wb + '_' + user_v + '.jpg',
                'type': 'image/jpg',
            }
            with open(filename, 'rb') as img:
                data['bits'] = xmlrpc_client.Binary(img.read())
            response = client.call(media.UploadFile(data))
            thumbnail_id = response['id']

            page.title = user_v
            page.parent_id = parentPages[category]
            if (category2):
                cat = category2
            else:
                cat = category

            page.excerpt = ''

            page.terms_names = {
                'category': [cat],
                'owner': [owner.fullname],
            }

            page.custom_fields = []
            page.custom_fields.append({
                'key': 'dashboard_link', 'value': ''
            })
            page.custom_fields.append({
                'key': 'dashboard_tips', 'value': ''
            })
            page.custom_fields.append({
                'key': 'context', 'value': ''
            })
            page.custom_fields.append({
                'key': 'dashboard_link', 'value': "<script type='text/javascript' src='https://tableau.kipp.org/javascripts/api/viz_v1.js'></script><div class='tableauPlaceholder' style='width: 1200px; height: 100px;'><object class='tableauViz' width='1200' height='1000' style='display:none;'><param name='host_url' value='https%3A%2F%2Ftableau.kipp.org%2F' /> <param name='embed_code_version' value='3' /> <param name='site_root' value='&#47;t&#47;KIPPLA' /><param name='name' value='" + user_wb.replace(' ', '').replace('&', '').replace('/', '').replace('(', '').replace(')', '') + "&#47;" + user_v.replace(' ', '').replace('&', '').replace('/', '').replace('(', '').replace(')', '') + "' /><param name='tabs' value='no' /><param name='toolbar' value='yes' /><param name='showAppBanner' value='false' /></object></div>"
            })
            page.custom_fields.append({
                'key': 'staging_dashboard_link', 'value': "<script type='text/javascript' src='https://stg-tableau.kipp.org/javascripts/api/viz_v1.js'></script><div class='tableauPlaceholder' style='width: 1200px; height: 100px;'><object class='tableauViz' width='1200' height='1000' style='display:none;'><param name='host_url' value='https%3A%2F%2Fstg-tableau.kipp.org%2F' /> <param name='embed_code_version' value='3' /> <param name='site_root' value='&#47;t&#47;KIPPLA' /><param name='name' value='" + user_wb.replace(' ', '').replace('&', '').replace('/', '').replace('(', '').replace(')', '') + "&#47;" + user_v.replace(' ', '').replace('&', '').replace('/', '').replace('(', '').replace(')', '') + "' /><param name='tabs' value='no' /><param name='toolbar' value='yes' /><param name='showAppBanner' value='false' /></object></div>"
            })

            # thumbnail id of the image we just uploaded
            page.thumbnail = thumbnail_id
            # publish page
            page.post_status = 'publish'
            # create page
            page.id = client.call(posts.NewPost(page))
            print (Fore.YELLOW +'\nAdded ' + category + ': ' + view.name + '\n' +
                   'Owner: ' + owner.fullname + '\n')
        
        print (Fore.RED + '\nFinished Adding all pages')

    else:
        print ('n')
