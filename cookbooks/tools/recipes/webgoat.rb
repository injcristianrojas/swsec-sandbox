# Paquetes Ubuntu
pkgs = %w{tomcat6}

pkgs.each do |pkg|
    package pkg do
        action :install
    end
end

script "webgoat_download" do
  interpreter "bash"
  user "root"
  code <<-EOH
  wget http://webgoat.googlecode.com/files/WebGoat-5.4.war -O /tmp/WebGoat.war
  cp /tmp/WebGoat.war /var/lib/tomcat6/webapps/WebGoat.war
  EOH
end

template "/etc/tomcat6/tomcat-users.xml" do
  source "tomcat-users.xml.erb"
  owner "root"
  group "tomcat6"
  mode "0640"
  variables(
    :users => node[:tomcat][:users],
    :roles => node[:tomcat][:roles]
  )
end

script "tomcat_restart" do
  interpreter "bash"
  user "root"
  code <<-EOH
  service tomcat6 restart
  EOH
end
