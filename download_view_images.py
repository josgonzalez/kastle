import tableauserverclient as TSC
import xlrd

tableau_auth = TSC.TableauAuth('kippndc\josgonzalez', 'Lakai219', 'KIPPLA')
server = TSC.Server('https://tableau.kipp.org/', use_server_version=True)

with server.auth.sign_in(tableau_auth):
    req_options = TSC.RequestOptions()
    req_options.page_size(1000)
    all_views, pagination_item = server.views.get(req_options)

    for view in all_views :
        workbook = server.workbooks.get_by_id(view.workbook_id)
        if (workbook.project_name == 'KASTLE') :
            server.views.populate_preview_image(view)
            filepath = "/Users/jgonzalez/Desktop/view_preview_images/" + workbook.name + "_" + view.name + ".jpg"
            print (filepath)
            print ('')
    
            with open(filepath, "wb") as image_file:
                image_file.write(view.preview_image)

   
