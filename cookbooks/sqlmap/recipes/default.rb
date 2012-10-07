include_recipe "git"

git "/home/vagrant/sqlmap/" do
  repository "git://github.com/sqlmapproject/sqlmap.git"
  reference "master"
  action :sync
  user "vagrant"
  group "vagrant"
end