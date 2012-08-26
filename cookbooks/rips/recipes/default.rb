include_recipe "apache2"
include_recipe "mysql::server"
include_recipe "php"
include_recipe "php::module_mysql"
include_recipe "apache2::mod_php5"

package "curl"
package "unzip"

script "rips_install" do
  interpreter "bash"
  user "root"
  cwd "/tmp"
  code <<-EOH
  wget http://ufpr.dl.sourceforge.net/project/rips-scanner/rips-0.53.zip -O rips.zip
  unzip -d /var/www/rips -o rips.zip
  EOH
end

service "apache2" do
  action :restart
end