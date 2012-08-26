include_recipe 'basic-config::locale_correction'

%w[htop vim].each{ |package_name| package package_name }