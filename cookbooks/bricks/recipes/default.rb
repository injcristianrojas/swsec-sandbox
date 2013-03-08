include_recipe "build-essential"
include_recipe "apache2"
include_recipe "apache2::mod_php5"
include_recipe "mysql::server"
include_recipe "php"
include_recipe "php::module_mysql"

script "bricks_install" do
  interpreter "bash"
  user "root"
  cwd "/tmp"
  code <<-EOH
  cp -rf /vagrant_folder/bricks /var/www/bricks
  chmod 755 -R /var/www/bricks
  chmod 777 /var/www/bricks/upload-1/uploads/
  EOH
end

script "bricks_database_create" do
  interpreter "bash"
  user "root"
  cwd "/tmp"
  code <<-EOH
  mysql -uroot -p#{node[:mysql][:server_root_password]} -e "CREATE DATABASE IF NOT EXISTS bricks"
  EOH
end

template "/var/www/bricks/LocalSettings.php" do
  source "LocalSettings.php.erb"
  owner "root"
  group "root"
  mode "0755"
  variables(
    :root_pass => node[:mysql][:server_root_password]
  )
end

script "bricks_database_populate" do
  interpreter "bash"
  user "root"
  cwd "/tmp"
  code <<-EOH
  mysql -uroot -p#{node[:mysql][:server_root_password]} bricks < /var/www/bricks/config/bricks.sql
  EOH
end

service "apache2" do
  action :restart
end
