# Paquetes Ubuntu
pkgs = %w{vim htop git-core unzip curl}

pkgs.each do |pkg|
    package pkg do
        action :install
    end
end
