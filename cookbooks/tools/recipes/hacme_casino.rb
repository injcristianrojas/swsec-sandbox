directory "/opt/hacme_casino" do
  owner "vagrant"
  group "vagrant"
  mode "0755"
  action :create
end

git "/opt/hacme_casino" do
  repository "git://github.com/injcristianrojas/hacme_casino.git"
  reference "master"
  action :sync
end
