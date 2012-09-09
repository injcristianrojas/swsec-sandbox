include_recipe "apache2"
include_recipe "mysql::server"
include_recipe "php"
include_recipe "php::module_mysql"
include_recipe "apache2::mod_php5"

package "curl"
package "unzip"

script "mutillidae_install" do
  interpreter "bash"
  user "root"
  cwd "/tmp"
  code <<-EOH
  wget http://ufpr.dl.sourceforge.net/project/mutillidae/mutillidae-project/LATEST-mutillidae-2.3.2.zip -O mutillidae.zip
  unzip -d /var/www/ -o mutillidae.zip
  EOH
end

node[:mysql][:server_root_password].each do |password|
  template "/var/www/mutillidae/classes/MySQLHandler.php" do
    source "MySQLHandler.php.erb"
    mode '0644'
    variables(
      :root_pass => password
    )
  end
end

service "apache2" do
  action :restart
end

script "mutillidae_db_setup" do
  interpreter "bash"
  user "root"
  code <<-EOH
  curl http://localhost/mutillidae/set-up-database.php
  EOH
end
