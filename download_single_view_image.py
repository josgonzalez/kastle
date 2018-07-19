import tableauserverclient as TSC
import xlrd

tableau_auth = TSC.TableauAuth('kippndc\josgonzalez', 'Lakai219', 'KIPPLA')
server = TSC.Server('https://tableau.kipp.org/', use_server_version=True)

view = raw_input("Enter View Name: ")

with server.auth.sign_in(tableau_auth):
    req_options = TSC.RequestOptions()
    req_options.filter.add(TSC.Filter(TSC.RequestOptions.Field.Name,
                                     TSC.RequestOptions.Operator.Equals,
                                      view))
    matches, pagination_item = server.views.get(req_options)

    for view in matches :
        workbook = server.workbooks.get_by_id(view.workbook_id)
        print (workbook.name + "_" + view.name)
        server.views.populate_preview_image(view)
        filepath = "/Users/jgonzalez/Desktop/view_preview_images/" + workbook.name + "_" + view.name + ".png"
        print (filepath)
        
        with open(filepath, "wb") as image_file:
            image_file.write(view.preview_image)

