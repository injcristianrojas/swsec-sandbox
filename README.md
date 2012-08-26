# Sandbox para Capacitación en Seguridad de Software #

## Introducción ##

El presente proyecto consiste en una máquina virtual linux que contiene
herramientas web débiles a propósito, con el objeto de ser utilizada
como parte de un curso de Seguridad de Software.

En este minuto, incluye los siguientes proyectos:

* [OWASP Mutillidae](http://www.irongeek.com/i.php?page=security/mutillidae-deliberately-vulnerable-php-owasp-top-10):
Aplicación PHP+MySQL que implementa el [OWASP Top 10](https://www.owasp.org/index.php/Category:OWASP_Top_Ten_Project).
* [OWASP WebGoat](https://www.owasp.org/index.php/Category:OWASP_WebGoat_Project): Aplicación JEE especial para
clases de seguridad de aplicaciones.

## Requerimientos del sistema ##

Para su ejecución, se requiere tener instalado el siguiente software:

* VirtualBox
* Vagrant

Ambos software se encuentran disponibles tanto para Windows, como para Mac, como para Linux.

### Windows ###

Para instalar VirtualBox en Windows, bájelo desde <http://www.virtualbox.org>. Para instalar Vagrant,
baje el paquete .msi desde <http://downloads.vagrantup.com/>.

### Linux ###

Las instrucciones aquí indicadas son para Ubuntu. Para otras distribuciones Linux utilice el sistema
de paquetes que estas provee. Instale VirtualBox y Ruby utilizando desde la linea de comandos
`sudo apt-get install virtualbox rubygems`. Terminado ese proceso, instale Vagrant utilizando
`gem install vagrant`. 

### Mac ###

Para instalar VirtualBox en Windows, bájelo desde <http://www.virtualbox.org>. Si ruby se encuentra instalado
en su equipo, instale Vagrant utilizando `gem install vagrant`. Si no está, baje el paquete .dmg desde
<http://downloads.vagrantup.com/>.

## Instalación de la máquina virtual ##

En un directorio de su máquina, escriba

    git clone ...

Esto generará un directorio llamado ``.
Ingrese a él y baje allí la máquina virtual base Ubuntu Lucid 32 desde <http://files.vagrantup.com/lucid32.box>.
Ya estando lista la máquina virtual base, agréguela a Vagrant escribiendo

    vagrant box add lucid32 lucid32.box

Ya estando agregada la máquina virtual, lo que resta es prepararla. Escriba

    vagrant up

Con eso comenzará el proceso de aprovisionamiento. La máquina se encenderá y empezará a bajar
el software que necesita para funcionar. Cuando termine este proceso, escriba

    vagrant ssh

y tendrá acceso a la máquina. Para detenerla Usted puede pausarla o apagarla por completo. Si
desea pausarla haga `vagrant suspend`. Para iniciarla de nuevo escriba `vagrant resume`. Si desea
apagarla escriba `vagrant halt`, y para reiniciarla escriba `vagrant reload --no-provision`