# Sandbox para Capacitación en Seguridad de Software

## Introducción

El presente proyecto consiste en una máquina virtual linux que contiene
herramientas web débiles a propósito, con el objeto de ser utilizada
como parte de un curso de Seguridad de Software.

En este minuto, incluye los siguientes proyectos:

* [OWASP Mutillidae](http://www.irongeek.com/i.php?page=security/mutillidae-deliberately-vulnerable-php-owasp-top-10):
Aplicación PHP+MySQL que implementa el [OWASP Top 10](https://www.owasp.org/index.php/Category:OWASP_Top_Ten_Project).
* [OWASP Bricks](http://sechow.com/bricks/): Aplicación PHP+MySQL que permite ayudar a los estudiantes con
vulnerabilidades típicas (llamadas Bricks)

## Requerimientos del sistema

Para su ejecución, se requiere tener instalado el siguiente software:

* VirtualBox
* Vagrant

Ambos software se encuentran disponibles tanto para Windows, como para Mac, como para Linux.

### Windows

Para instalar VirtualBox en Windows, bájelo desde <http://www.virtualbox.org>. Para instalar Vagrant,
baje el paquete .msi desde <http://downloads.vagrantup.com/>.

### Linux

Las instrucciones aquí indicadas son para Ubuntu. Para otras distribuciones Linux utilice el sistema
de paquetes que estas provee. Instale VirtualBox y Ruby utilizando desde la linea de comandos
`sudo apt-get install virtualbox rubygems`. Terminado ese proceso, instale Vagrant utilizando
`gem install vagrant`. 

### Mac

Para instalar VirtualBox en Mac, bájelo desde <http://www.virtualbox.org>. Si ruby se encuentra instalado
en su equipo, instale Vagrant utilizando `gem install vagrant`. Si no está, baje el paquete .dmg desde
<http://downloads.vagrantup.com/>.

## Instalación de la máquina virtual

En un directorio de su máquina, escriba

    git clone git://github.com/injcristianrojas/swsec-sandbox.git

Esto generará un directorio llamado `swsec-sandbox`. Ingrese a él y baje allí la máquina virtual
base Ubuntu Precise (32 bits) desde <http://files.vagrantup.com/precise32.box>.
Ya estando lista la máquina virtual base, agréguela a Vagrant escribiendo

    vagrant box add precise32 precise32.box

Ya estando agregada la máquina virtual, lo que resta es prepararla. Escriba

    vagrant up

Con eso comenzará el proceso de aprovisionamiento. La máquina se encenderá y empezará a bajar
el software que necesita para funcionar. Cuando termine este proceso, escriba

    vagrant ssh

y tendrá acceso a la máquina. Para detenerla Usted puede pausarla o apagarla por completo. Si
desea pausarla haga `vagrant suspend`. Para iniciarla de nuevo escriba `vagrant resume`. Si desea
apagarla escriba `vagrant halt`, y para reiniciarla escriba `vagrant reload --no-provision`

## Uso de la máquina virtual

La dirección IP de la máquina virtual es 33.33.33.100. Si desea acceder a las herramientas
web vulnerables ingrese a:

* OWASP Mutillidae: http://33.33.33.100/mutillidae/
* OWASP Bricks: http://33.33.33.100/bricks/

## Licencias

Las licencias del software distribuido son las siguientes:

* OWASP Mutillidae: [Creative Commons Reconocimiento - CompartirIgual 3.0](http://creativecommons.org/licenses/by-sa/3.0/)
* OWASP Bricks: [Licencia Apache 2.0](https://www.apache.org/licenses/LICENSE-2.0.html)